 $(document).ready(function(){
    $(".import-warn").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        yn = confirm("Are you sure you want to re-import Harvest data?\n\rThis will delete all manual entries (earnings and deductions).");
        if(yn){
            link = $(this).attr("href");
//            alert(link);
            window.location= link;
        }
    });
    
     $(".uncomplete-warn").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        yn = confirm("Are you sure you want to uncomplete this payroll? This will put payroll in 'processing' status and allow re-import");
        if(yn){
            link = $(this).attr("href");
//            alert(link);
            window.location= link;
        }
    });
    
});