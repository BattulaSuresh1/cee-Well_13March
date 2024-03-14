$('#roleForm').validate({
  rules: {
      name: {
        required: true,
        maxlength: 50
      },
    
  },
  highlight: function(input) {
      $(input).parents('.form-line').addClass('error');
  },
  unhighlight: function(input) {
      $(input).parents('.form-line').removeClass('error');
  },
  errorPlacement: function(error, element) {
      $(element).parents('.form-group').append(error);
  }
    
});
