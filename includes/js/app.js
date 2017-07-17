function formatDate(date) {
  var monthNames = [
    "01", "02", "03",
    "04", "05", "06", "07",
    "08", "09", "10",
    "11", "12"
  ];

  var monthIndex = date.getMonth();
  var year = date.getFullYear();


  return year + '-' + monthNames[monthIndex];
}


jQuery.fn.sortElements = (function(){
 
    var sort = [].sort;
 
    return function(comparator, getSortable) {
 
        getSortable = getSortable || function(){return this;};
 
        var placements = this.map(function(){
 
            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,
 

                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );
 
            return function() {
 
                if (parentNode === this) {
                    throw new Error(
                        "You can't sort elements if any one is a descendant of another."
                    );
                }
 
                // Insert before flag:
                parentNode.insertBefore(this, nextSibling);
                // Remove flag:
                parentNode.removeChild(nextSibling);
 
            };
 
        });
 
        return sort.call(this, comparator).each(function(i){
            placements[i].call(getSortable.call(this));
        });
 
    };
 
})();


function sortBy() {
    var th = $('th'),
                li = $('li'),
                inverse = false;
            
            th.click(function(){
                
                var header = $(this),
                    index = header.index();
                    
                header
                    .closest('table')
                    .find('td')
                    .filter(function(){
                        return $(this).index() === index;
                    })
                    .sortElements(function(a, b){
                        
                        a = $(a).text();
                        b = $(b).text();
                        
                        return (
                            isNaN(a) || isNaN(b) ?
                                a > b : +a > +b
                            ) ?
                                inverse ? -1 : 1 :
                                inverse ? 1 : -1;
                            
                    }, function(){
                        return this.parentNode;
                    });
                
                inverse = !inverse;
                
            });

}




$(document).ready(function() {

/*  var option = $('#selectMonth').find(':selected').attr('value');*/

	$('#mainContent').load('main-content.php?selectMonth=' + formatDate(new Date()));
  $('#selectMonthFile').load('selectmonth.php');
  $('#deletecategoryfile').load('deletecategory.php');

	$("#editDiv").hide();

var d = new Date()

$('#time').html('<li>' + d.toDateString() + '</li>');


	$('body').on('change', '#selectMonth', function() {
	var option = $('#selectMonth').find(':selected').attr('value'),
			url = 'main-content.php?selectMonth='+option

	
    $.ajax({
      data: {option : option},
      type: 'post',
      url: url,
      success: function() {
       $('#mainContent').load(url);
       console.log(option);
              $('#selectMonthFile').load('selectmonth.php?selectMonth='+option);
      }
    });
	});


	 $('body').on('submit', '#categoryInsert', function(e){
      //preventing default submt to refresh page
      e.preventDefault();
      //creating var
      var data = $(this).serialize()
      var option = $('#selectMonth').find(':selected').attr('value'),
			url = 'main-content.php?selectMonth='+option


      $.ajax({
         data: data,
         type: 'post',
         url: 'insert.php',
         success: function(data){
         	console.log(data)
         		 if(data == '') {$('#mainContent').load(url);}else{
          $('#messageCat').html('<div class="alert alert-info">'+data+'</div>').fadeIn('slow').delay('1000').fadeOut('slow');
          $('#mainContent').load(url);  
  $('#deletecategoryfile').load('deletecategory.php');
          }   
         },
      });
   });

  $('body').on('submit', '.delete', function(e){
      //preventing default submt to refresh page
      e.preventDefault();

      	var option = $('#selectMonth').find(':selected').attr('value'),
			url = 'main-content.php?selectMonth='+option

      //creating var
      var id = $(this).attr('rel');
      var form = $(this);
      var data = form.serialize();

          console.log(data)
console.log(id);
      $.ajax({
         data: data,
         type: 'post',
         url: 'insert.php?id='+id,
         success: function(data){
           $('#mainContent').load(url);
                         $('#selectMonthFile').load('selectmonth.php?selectMonth='+option);
         },
      });
   });

  $('body').on('change', '#deleteCategory', function() {
    var catName = $('#selectDeleteCat').val();
    var option = $('#selectMonth').find(':selected').attr('value'),
    url = 'main-content.php?selectMonth='+option
     var data = $(this).serialize();
    if(confirm('WARNING!!! if you confirm then you will lose all data from the Category you will not be able to recover it')){
            $.ajax({
               data: data,
               type: 'post',
               url: 'insert.php?categorydelete='+catName,
               success: function(data){
          console.log(data);
           $('#mainContent').load(url);
           $('#deletecategoryfile').load('deletecategory.php');
                         $('#selectMonthFile').load('selectmonth.php?selectMonth='+option);
         },
      });
  } else {
      $('#deletecategoryfile').load('deletecategory.php');
      $('#mainContent').load(url);
                    $('#selectMonthFile').load('selectmonth.php?selectMonth='+option);
    }
  });



	 $('body').on('submit', '.insertForm', function(e){
      //preventing default submt to refresh page
      e.preventDefault();
      //creating var
      	var option = $('#selectMonth').find(':selected').attr('value'),
			url = 'main-content.php?selectMonth='+option

      var data = $(this).serialize();
console.log(data);
      $.ajax({
         data: data,
         type: 'post',
         url: 'insert.php',
         success: function(data){
         		 if(!$.trim(data)) {$('#mainContent').load(url);
               $('#selectMonthFile').load('selectmonth.php');
                             $('#selectMonthFile').load('selectmonth.php?selectMonth='+option);
            }else{
          $('#mainContent').load(url, function(){
            $('#message').html('<div class="alert alert-danger">'+data+'</div>').fadeIn('slow').delay('1000').fadeOut('slow');
            console.log(data);
              $('#selectMonthFile').load('selectmonth.php?selectMonth='+option);
          });   
          }   
         },

      });
   });

   	 $('body').on('submit', '.updateForm', function(e){
      //preventing default submt to refresh page
      e.preventDefault();
      //creating var
      	var option = $('#selectMonth').find(':selected').attr('value'),
			url = 'main-content.php?selectMonth='+option

      var id = $(this).attr('rel');
      var data = $(this).serialize();
console.log(data);
      $.ajax({
         data: data,
         type: 'post',
         url: 'insert.php?updateinsert='+id,
         success: function(data){
         		 if(data == '') {$('#mainContent').load(url);
              $('#selectMonthFile').load('selectmonth.php?selectMonth='+option);
            }else{
       /* $('#message').html('<div class="alert alert-danger">'+data+'</div>').fadeIn('slow').delay('1000').fadeOut('slow');*/
          $('#mainContent').load(url);  
                        $('#selectMonthFile').load('selectmonth.php?selectMonth='+option);  
          }   
         },

      });
   });

  $('body').on('click', '#editIncome', function(){
  	var id = $(this).attr('rel');
  	console.log(id);
   	$('#editDiv' + id).toggle();
   });


  $('body').on('change', '#doneTask', function(){
  	var id = $("input[name='doneTask']:checked");
  	var data = $(this).serialize();

  	   $.ajax({
         data: data,
         type: 'post',
         url: 'insert.php?taskDone='+id,
         success: function(data){
         	console.log(data);
         },
      });  	
  });
});
