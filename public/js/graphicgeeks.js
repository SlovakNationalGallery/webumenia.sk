  $(document).ready(function() {
    
    var myAccordion = new $.Zebra_Accordion('#Zebra_Accordion1', {
      'collapsible':  true
    });
    
    myAccordion.show(0); 
  });

/*

	02. Mobile Navigation
			
*/


/* -- MOBILE NAVIGATION -- */

	 $(function() {
	   
      // Create the dropdown base
      $("<select />").appendTo("nav .container .sixteen");
      
      // Create default option "Go to..."
      $("<option />", {
         "selected": "selected",
         "value"   : "",
         "text"    : "Menu"
      }).appendTo("nav select");
      
      // Populate dropdown with menu items
      $("nav #onepagenav a").each(function() {
       var el = $(this);
       $("<option />", {
           "value"   : el.attr("href"),
           "text"    : el.text()
       }).appendTo("nav select");
      });
      
	   // To make dropdown actually work
      $("nav select").change(function() {
        window.location = $(this).find("option:selected").val();
      });
	 
	 });
	 
	 