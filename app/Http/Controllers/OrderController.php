<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\OrderPayments;
use Illuminate\Http\Request;
use DB;
use Illuminate\Mail\Message;
use App\Models\Products;
use Illuminate\Support\Facades\Mail;
use PDF;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {

        $pageSize = $request->per_page ?? 25;

        $columns = ['*'];

        $pageName = 'page';

         $page = $request->current_page ?? 1;

         $search = $request->filter ?? "";

        $query = Order::with('orderitems')
            ->with(['orderpayments'])
            ->withSum('orderpayments','order_payments.paid_amount')
            ->orderBy('id', 'DESC');

        if ($request->has('customerId')) {
            $customerId = $request->customerId;
            if (!empty($customerId))
                $query->where('customer_id', $customerId);
        }

        if ($request->has('userId')) {
            $userId = $request->userId;
            if (!empty($userId))
                $query->where('user_id', $userId);
        }

        $query->where('status', '1');

        if (!empty($search)) {
            $query->where('id', 'LIKE', "%$search%");
            $query->orWhere('amount', 'LIKE', "%$search%");
             $query->orWhere('paidAmount', 'LIKE' , "%$search%");
        }
        
         $data = $query->paginate($pageSize,$columns,$pageName,$page);

        return response()->json($data);
    }

    public function store(Request $request)
    {
        try {

            $customerId = $request->customerId;
            $userId = $request->userId;
            $cartItems = cart::where(['customer_id' => $customerId, 'status' => 1])->get();
            $order = new Order();
            $order->customer_id = $customerId;
            $order->user_id = $userId;
            $order->order_status = '0';

            $discount = $request->discount ?? 0;
          

            $order->save();

            $total = 0;
            $productDiscount = 0;

            foreach ($cartItems as $key => $cart) {
                $orderItem = new OrderItems();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $cart['product_id'];
                $orderItem->prescription_id = $cart['prescription_id'];
                $orderItem->lenses_id = $cart['cart_lenses_id'];
                $orderItem->measurements_id = $cart['cart_measurements_id'];
                $orderItem->quantities = $cart['quantities'];
                $orderItem->price = $cart['price'];
                $orderItem->total_amount = $cart['total_amount']; 
                $orderItem->total_discount = $cart['total_discount'];
                $orderItem->save();

                if ($orderItem->save()) {
                    cart::where('id', $cart['id'])->delete();

                    $inventory = Inventory::where('product_id', $cart['product_id'])->where('status', '1')->first();

                    if ($inventory) {
                        $inventory->available = ((int) $inventory['available']) - (int) $cart['quantities'];
                        $inventory->save();
                    }

                }

                $total = $total + $cart['total_amount'];

                $productDiscount = $productDiscount + $cart['discount'];

            }

            $orderItem = Order::find($order->id);
            $orderItem->payment_type = $request->payment_type; // Store Payment Type
            $orderItem->payment_settlement = $request->payment_settlement; // Store Payment Settlement
            $orderItem->discount = $discount;
            $orderItem->product_discount = $productDiscount;
            $orderItem->amount = $total - $discount - $productDiscount;
            $orderItem->paid_amount = $request->amount;
            $orderItem->advance_amount = $request->advance_amount;
            // $orderItem->total_amount = $total; //added new filed by suresh
            // $orderItem->order_status = $request->order_status;
            
            // $orderItem->order_status = ($orderItem->paid_amount == $orderItem->amount) ? "1" : "0"; // Check if paid_amount equals amount
           
            $balanceAmount = $total - $discount - $request->amount;       
            
            // // Only subtract the discount if it is provided
            // if ($productDiscount > 0) {
            //     $orderItem->amount = $total - $discount - $productDiscount;
            // } else {
            //     $orderItem->amount = $total - $productDiscount;
            // }


            if ($balanceAmount === 0) {
                $orderItem->order_status = 1; // Balance amount equals paid amount
            } else {
                $orderItem->order_status = 0; // Balance amount doesn't equal paid amount
            }

            $orderItem->save();

            // $salesOrderCopyPdf = $this->generateSaleOrderPDF($order->id);
            $res = [
                'success' => true,
                'message' => 'Order created successfully.',
                'data' => $order,
                // 'sales_order_copy_pdf' => $salesOrderCopyPdf
            ];

            return response()->json($res);

        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' => $e->getMessage()
            ];
            return response()->json($res);
        }
    }

    public function show($id)
    {
        try {

            $data['order'] = Order::with('customer')
                ->with(['orderitems.product', 'orderitems.prescription.lenspower', 'orderitems.lens', 'orderitems.measurements.precalvalues', 'orderitems.measurements.thickness'])
                ->with(['orderpayments'])
                ->find($id);
            $res = [
                'success' => true,
                'message' => 'Order details.',
                'data' => $data,
            ];

            return response()->json($res);

        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' => $e->getMessage()
            ];
            return response()->json($res);
        }
    }

    public function update(Request $request, $id)
    {
        try {
     
            $order = Order::with('customer')
                ->with(['orderitems.product', 'orderitems.prescription.lenspower', 'orderitems.lens', 'orderitems.measurements.precalvalues', 'orderitems.measurements.thickness'])
                ->with(['orderpayments'])->find($id);
            $count = OrderPayments::where(['order_id' => $id])->count();

        
            if ($count == 0) {

                $order->payment_type = $request->payment_type;
                $order->payment_settlement = $request->payment_settlement;
                 $order->paid_amount = $request->amount;

                if ($request->has('orderStatus')) {
                    $order->order_status = $request->orderStatus;
                }
                $order->save();
            }
 
            $payment = new OrderPayments;
            $payment->order_id = $id;
            $payment->payment_type = $request->payment_type;
            $payment->paid_amount = $request->amount;

            $payment->save();
            
            $res = [
                'success' => true,
                'message' => 'Order updated successfully.',
                'data' => $order
            ];

            return response()->json($res);

        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' => $e->getMessage()
            ];
            return response()->json($res);
        }
    }

    

    public function getOrders(Request $request)
    {

        $pageSize = $request->per_page ?? 25;

        $columns = ['*'];

        $pageName = 'page';

        $page = $request->current_page ?? 1;

        $search = $request->filter ?? "";

        $query = Order::with('orderitems')
         ->withSum('orderpayments','order_payments.paid_amount')
        ->orderBy('id', 'DESC');

        if ($request->has('customerId')) {
            $customerId = $request->customerId;
            if (!empty($customerId))
                $query->where('customer_id', $customerId);
        }

        if ($request->has('userId')) {
            $userId = $request->userId;
            if (!empty($userId))
                $query->where('user_id', $userId);
        }

        $query->where('status', '1');

        if (!empty($search)) {
            $query->where('id', 'LIKE', "%$search%");
            // $query->orWhere('amount', 'LIKE', "%$search%");
        }

        $data = $query->paginate($pageSize, $columns, $pageName, $page);

        return response()->json($data);
    }

    public function generateSaleOrderPDF($orderId)
    {
       
        $order = Order::with('customer')
            ->with([
                'orderitems.product',
                // 'orderitems.orderitemstatus.orderItem',
                // 'orderitems.orderitemstatus.status',
                // 'orderitems.orderitemstatus.order_status',
                // 'orderitems.orderitemstatus.user'
            ])
            ->with(['orderpayments'])
            ->find($orderId);


        $pdf = PDF::loadView('sales-order-pdf', compact('order'));
        $folderPath = 'pdf/orders';
        $pdfPath = public_path($folderPath);
        $pdfName = 'sales_order_copy_' . $orderId . '.pdf';

        $pdf->save($pdfPath . '/' . $pdfName);
        // echo "Sales Order Copy". $pdfPath. '/' . $pdfName;

       

        $salesInvoicePdf = url($folderPath . "/" . $pdfName);
        $res = [
            'success' => true,
            'message' => 'Sales Invoice Generated successfully.',
            'sales_invoice_pdf' => $salesInvoicePdf,
            'order' => $order,
        ];
        

        return response()->json($res);

        // return view('sales-order-pdf', compact('order'));
    }

    function generateInvoiceNumber($orderId)
    {
        // Check if an invoice number is already associated with this order
        $existingInvoiceNumber = DB::table('orders')
            ->where('id', $orderId)
            ->value('invoice_number');

        if ($existingInvoiceNumber === null) {
            // If no invoice number is associated with the order, generate a new one
            $lastInvoiceNumber = DB::table('orders')->max('invoice_number');
            if ($lastInvoiceNumber === null) {
                $newInvoiceNumber = 1000;
            } else {
                $newInvoiceNumber = $lastInvoiceNumber + 1;
            }

            // Store the generated invoice number in the order record
            DB::table('orders')
                ->where('id', $orderId)
                ->update(['invoice_number' => $newInvoiceNumber]);
        } else {
            // Use the existing invoice number for this order
            $newInvoiceNumber = $existingInvoiceNumber;
        }

        return $newInvoiceNumber;
    }

    function generateSaleInvoicePDF($orderId)
    {
        $order = Order::with('customer')
            ->with([
                'orderitems.product',
                'orderitems.orderitemstatus.orderItem',
                'orderitems.orderitemstatus.status',
                'orderitems.orderitemstatus.user'
            ])
            ->with(['orderpayments'])
            ->find($orderId);

        $invoiceNumber = $this->generateInvoiceNumber($orderId);
        $order->update(['invoice_number' => $invoiceNumber]);
        // $order->invoice_number = $invoiceNumber;
        // $order->order_status = '1';
        $order->save();

        $pdf = PDF::loadView('sales-invoice-pdf', compact('order'));
        $folderPath = 'pdf/invoices';
        $pdfPath = public_path($folderPath);
        $pdfName = 'sales_invoice_copy_' . $orderId . '.pdf';

        $pdf->save($pdfPath . '/' . $pdfName);
        // echo "Sales Order Copy". $pdfPath . '/' . $pdfName;

        $salesInvoicePdf = url($folderPath . "/" . $pdfName);

        $res = [
            'success' => true,
            'message' => 'Sales Invoice Generated successfully.',
            'invoice_number' => $invoiceNumber,
            'sales_invoice_pdf' => $salesInvoicePdf,
            'order' => $order,
        ];
        
        return response()->json($res);

        // return view('sales-invoice-pdf', compact('order'));
    }

    public function cancel(Request $request)
    {
        $orderId = $request->orderId;

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.'
            ]);
        }


        // Check if the current order_status is '0' or '1' before canceling
        if ($order->order_status == 0 || $order->order_status == 1) {

            Order::where('id', $orderId)->update(['order_status' => '2']);
            return response()->json([
                'success' => true,
                'message' => 'Order canceled successfully.',
                'data' => 2
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be canceled because its status is not 0 or 1.',
                'data' => $order
            ]);
        }
    }
}