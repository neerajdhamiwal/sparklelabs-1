(function($) {
  // your standard jquery code goes here with $ prefix
  // best used inside a page with inline code,
  // or outside the document ready, enter code here

  $(document).ready(function() {

    jQuery('#upm-thankyou-form').prepend(jQuery('<div class="inter-share-text">Share your purchase and get 5% off your next order. </div>'));
    
    $(".twitter-share-button").click(function() {
      var data = $(this).siblings('textarea').val();
      var href = $(this).attr('href') + '?text=' + data;
      $(this).attr('href', href);
      // console.log(href);
    });

    $(".btn-mail").click(function() {
      var data = $(this).siblings('textarea').val();
      var href = $(this).attr('href') + '&body=' + data;
      $(this).attr('href', href);
      // console.log(href);
    });

    $(".btn-pin").click(function() {
      var data = $(this).siblings('textarea').val();
      var href = $(this).attr('href') + '&description=' + data;
      $(this).attr('href', href);
      // console.log(href);
    });

    $("#edit-share-title-select").change(function() {
      var pid = jQuery("#edit-share-title-select option:selected").val();
      $("#product-" + pid + "-wrapper").show().siblings("div").hide();

    });
    jQuery("#tweetshare").click(function() {
      jQuery(".twitter-share-button").show().siblings("a").hide();
      jQuery("#tweetshare").siblings("div").css("border", "none");
      jQuery("#tweetshare").css("border", "1px solid");

      jQuery("#tweetshare").css("border-bottom", "none");

      jQuery("#tweetshare a ").css("color", "gray");
      jQuery("#tweetshare").siblings("div").css("border-bottom", "1px solid ");

    });
    jQuery("#pinshare").click(function() {
      jQuery("#pinshare").css("border", "1px solid");
      jQuery("#pinshare").css("border-bottom", "none");
      jQuery(".btn-pin").show().siblings("a").hide();
      jQuery("#pinshare").siblings("div").css("border", "none");
      jQuery("#pinshare a ").css("color", "gray");
      jQuery("#pinshare").siblings("div").css("border-bottom", "1px solid ");


    });
    jQuery("#emailshare").click(function() {
      jQuery("#emailshare").css("border", "1px solid");
      jQuery(".btn-mail").show().siblings("a").hide();
      jQuery("#emailshare").siblings("div").css("border", "none");
      jQuery("#emailshare").css("border-bottom", "none");
      jQuery("#emailshare a ").css("color", "gray");
      jQuery("#emailshare").siblings("div").css("border-bottom", "1px solid ");

    });

    jQuery("#fbshare").click(function() {

      jQuery(".btn-facebook").show().siblings("a").hide();
      jQuery("#fbshare").siblings("div").css("border", "none");
      jQuery("#fbshare").siblings("div").css("border-bottom", "1px solid ");
      jQuery("#fbshare").css("border", "1px solid");
      jQuery("#fbshare a ").css("color", "gray");
      jQuery("#fbshare").css("border-bottom", "none");


    });
    jQuery(".btn-facebook").show().siblings("a").hide();
    jQuery("#fbshare").siblings("div").css("border", "none");
    jQuery("#fbshare").css("border", "1px solid");
    jQuery("#fbshare a ").css("color", "gray");
    jQuery("#fbshare").siblings("div").css("border-bottom", "1px solid ");
    jQuery("#fbshare").css("border-bottom", "none");


  });
})(jQuery);
