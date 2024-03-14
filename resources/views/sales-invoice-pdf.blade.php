<!DOCTYPE html> <html lang="en"> <head>
<meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta
    http-equiv="X-UA-Compatible" content="ie=edge">
<title>Sales Invoice</title>
<style>
body { font-family:Arial, Helvetica, sans-serif; padding:0; margin:0;
    font-size: 12px; } body , p , table { font-size: 12px; letter-spacing: 0; } p{ padding:1px; margin:0; } /* Add
    styles for the table */ table.border{ border-collapse: collapse; /* Collapse table borders */ width: 100%; border:
    1px solid black; /* Set the border color and width */ } table.border th, table.border td { border: 1px solid black;
    /* Set cell borders */ padding: 2px; /* Add padding to cells for spacing */ text-align: right; /* Align cell content
    to the left */ } table.noborder{ border-collapse: collapse; /* Collapse table borders */ width: 100%; border:0; /*
    Set the border color and width */ } table.noborder th, table.noborder td { border: 0; /* Set cell borders */
    padding: 2px; /* Add padding to cells for spacing */ text-align: left; /* Align cell content to the left */ }
    table.productborder { border-collapse: collapse; border: 1px solid #ccc; width: 100%; } table.productborder th,
    table.productborder td { border: 1px solid #ccc; /* Set cell borders */ padding: 2px; /* Add padding to cells for
    spacing */ text-align: right; /* Align cell content to the left */ } .productlist { vertical-align: top; height:
    100px; } table.sideborder { width: 100%; } table.sideborder th { border-right: solid 2px #ccc; } </style>
    </head>

    <body>
        <table class="border">
            <tr>
                <td>
                    <p style="text-align: center; font-weight:bold">SALES INVOICE</p>
                </td>
            </tr>
            <tr>
                <td>
                    <table class="noborder">
                        <tr>
                            <td>
                                <!-- <h1>Pcube </h1> -->
                                <!-- <img src="D:\xampp\htdocs\project p_cube\pcube_api\public\image\Pcube.PNG" alt=""> -->
                                <!-- <img src="{{ public_path('image/p_cubelogo.png') }}" alt="Pcube"> -->
                                <img width="100" src="{{ public_path('storage/uploads/1650279123_new-logo.png') }}" alt="">
                            </td>
                            <td style="text-align: center">
                                <p><strong>POKARNA TEXTILES</strong>
                                <p>
                                <p> GST NO : 36AAYFP5587H1ZA
                                <p>
                                <p>SURYA TOWERS , S.P ROAD , SECUNDERABAD - 500003 , TELANGANA , TEL :
                                    +91-040-66446777,9100776666
                                <p>
                                <p>E-mail:tekroi@gmail.com, www.tekroi.com
                                <p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table class="noborder">
                        <tr>
                            <td>Sales Invoice No: PCI-{{ $order['invoice_number'] }} </td>
                            <td>Trail Date: {{ $order['trail_date'] ? date('d/m/Y', strtotime($order['trail_date'])) :
                                null }}</td>
                            <td>Delivery Date: {{ $order['delivery_date'] ? date('d/m/Y',
                                strtotime($order['delivery_date'])) : null}}</td>
                            <td>Order Date: {{ date('d/m/Y', strtotime($order['created_at'])) }} </td>
                        </tr>
                        <tr>
                            <td>Sale Order No: PCO0{{ $order['id'] }}</td>
                            <td>Name : {{ $order['customer']['name']}}</td>
                            <td>Ph No: {{ $order['customer']['phone']}}</td>
                            {{-- <td>Gstno:</td> --}}
                            <td>Date: {{ date ('d/m/Y')}}</td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="productlist">
                    <table class="productborder">
                        <tr>
                            <th width="5%" style="text-align: center">Sr.</th>
                            <th width="55%" style="text-align: left" >Particulars.</th>
                            <th width="10%" style="text-align: center">Product price.</th>
                            <th width="10%" style="text-align: center">Qty.</th>
                             <th width="10%" style="text-align: center"> Discount</th>
                           
                           
                            <!-- <th width="10%">Rate.</th>
                            <th width="10%">Gst.</th>
                            <th width="10%">Cgst.</th>
                            <th width="10%">Sgst.</th> -->
                            <th width="10%" style="text-align: center">Total.</th>

                        </tr>
                        @php
                        $totQty = 0;
                        @endphp
                        @foreach ($order['orderitems'] as $orderItem)
                        @php
                        $totQty += $orderItem['quantities'];

                        $cgst = $orderItem['gst_amount'] / 2;
                        $sgst = $orderItem['gst_amount'] / 2;
                        @endphp
                        <tr>
                            <td style="text-align: center">{{ $loop->index + 1 }}</td>
                            <td style="text-align: left">{{ $orderItem['product']['name']}}</td>
                            <td style="text-align: center">{{ number_format ($orderItem['price'],2) }}</td>
                            <td style="text-align: center">{{ $orderItem['quantities'] }}</td>
                            <td  style="text-align: center">{{ number_format ($orderItem['discount'],2) }}</td>
                           
                           
                            <td  style="text-align: center">{{ number_format($orderItem['price' ]*  $orderItem['quantities'] ,2)}}</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>

        <br>

        <table class="sideborder">
             <tr>
            <th  width="10%"></th>
            <th  width="60%">Total ({{ number_format((($order['amount']) + ($order['product_discount'])) +($order['discount'])  ,2)}})</th>
            <th  width="10%">{{ $totQty }}</th>
            {{-- <th  width="10%"  >{{ number_format((($order['amount']) + ($order['product_discount'])) / 2,2)}}</th> --}}
            <th  width="10%"  >{{ number_format(($order['product_discount']),2)}}</th>
           
            <th  width="10%"  style="text-align: right"></th>
        </tr>
        </table>
        <br>

       
        <table class="border">

             <tr>
             <td width="75%" rowspan="4">&nbsp;</td>
                <td style="text-align: left">Total Amount: {{ number_format(($order['amount']), 2) }}</td>
            </tr>
            <tr>
                <td style="text-align: left">Paid Amount: {{ number_format(( $order->calculateTotalPaidAmount()), 2) }}</td>
            </tr>
            @if ($order['discount'] > 0)
           
            <tr>
                <td style="text-align: left"> Special Discount : {{ number_format($order['discount'],2) }}</td> 
            </tr>

            @endif

            <tr>
                <td style="text-align: left">Balance : {{ number_format(($order['amount'] -
                $order->calculateTotalPaidAmount()),  2) }} </td>
            </tr>

            <tr>
            <td>
                <p><b>For Pokarna Textiles</b></p>
                Authorised sign:
                <br /><br /><br /><br /><br /><br />
            </td>
            </tr>
         </td>
    </table>
    
            <p>Remarks: {{ $order['remarks'] }} </p>
        <br />
        <p style="text-align: center">THANK YOU VISIT AGAIN  .</p>
    </body>

    </html>
