Date.prototype.today = function () { 

var monthNames = [
  "January", "February", "March",
  "April", "May", "June", "July",
  "August", "September", "October",
  "November", "December"
];

var day = this.getDate();
var monthIndex = this.getMonth();
var year = this.getFullYear();

console.log(day, monthNames[monthIndex], year);
return (monthNames[monthIndex] + ' ' +day +','+ year);

}

// For the time now
Date.prototype.timeNow = function () {
   var ampm =  this.getHours() >= 12 ? 'PM' : 'AM';
   return ((this.getHours() < 10)?"0":"") + this.getHours() +":"+ ((this.getMinutes() < 10)?"0":"") + this.getMinutes() +":"+ ((this.getSeconds() < 10)?"0":"") + this.getSeconds() +' '+ ampm;
}



function removeURLParameter(url, parameter) {
    //prefer to use l.search if you have a location/link object
    var urlparts= url.split('?');   
    if (urlparts.length>=2) {

        var prefix= encodeURIComponent(parameter)+'=';        
        var pars= urlparts[1].split(/[&;]/g);

        console.log(pars);

        //reverse iteration as may be destructive
        for (var i= pars.length; i-- > 0;) {    
            //idiom for string.startsWith
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                pars.splice(i, 1);
            }
        }

        url= urlparts[0]+'?'+pars.join('&');
        return url;
    } else {
        return url;
    }
}


function now(){
    var myDate = new Date();
    var prettyDate = myDate.getFullYear()+'-'+('0'+(myDate.getMonth()+1)).slice(-2)+ '-' + ('0'+(myDate.getDate())).slice(-2);      
    return prettyDate;
 };
  

function comma(yourNumber) {
    if(typeof(yourNumber) == 'undefined'){
      return 0;
    }
    //Seperates the components of the number
    var n= yourNumber.toString().split(".");
    //Comma-fies the first part
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return n.join(".");
}

function remove_comma(number){
    return number.replace(/,/g,'');
}

function commaSeparateNumber(val){
    while (/(\d+)(\d{3})/.test(val.toString())){
      val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    }
    return val;
}

function average(a,b){
  return ((+a+ +b)/2);
}
function sum(a,b){
  return (+a+ +b);
}

function label(status){
    switch (status.toUpperCase()) {
      case 'FALSE':
        return "<span class='label label-warning'>P</span>";
        break;
      case 'TRUE' :
        return "<span class='label label-success'><i class='fa fa-check'></i></span>";
        break;
      case "ACTIVE" :
        return "<span class='label label-success'>Active</span>";
      case "REJECTED" :
        return "<span class='label label-danger'>REJECTED</span>";
      break;      
      case "RECEIVE":
      case "RECEIVED":
        return "<span class='label label-info'>Received</span>";
      break;
      case "CANCEL" : 
        return "<span class='label label-danger'>Cancelled</span>";
      break;
      case "CLOSED" : 
        return "<span class='label label-danger'>closed</span>";
      break;
      case "CANCELLED" : 
        return "<span class='label label-danger'>Cancelled</span>";
      break;
      case "APPROVED" : 
        return "<span class='label label-success'>Approved</span>";
      break;
      case "REQUESTED" :
        return "<span class='label label-warning'>Requested</span>";
        break;
      case "AVAILABLE" :
        return "<span class='label label-success'>Available</span>";
        break;  
      case "UNDER REPAIR" :
        return "<span class='label label-danger'>Under Repair</span>";
        break;
      case "UNPOSTED" :
        return "<span class='label label-warning'>UNPOSTED</span>";
        break;  
      case "POSTED" :
        return "<span class='label label-success'>POSTED</span>";
        break;  

      case "ABSENT" :
        return "<span class='label label-danger'>ABSENT</span>";
        break;  

      case "RESTDAY" :
        return "<span class='label label-success'>RESTDAY</span>";
        break;
        
      case "NOT SERVED" :
        return "<span class='label label-danger'>NOT SERVED</span>";
        break;
      case "PENDING" :
        return "<span class='label label-warning'>PENDING</span>";
        break;
      default :
        return status;
      break;
          
    }
}


var label2 = function(status){
  switch(status.toUpperCase()){
      case "APPROVED":
        return "<span class='label label-warning'>WAITING</span>";
      break;
      case "PARTIAL":
        return "<span class='label label-info'>PARTIAL</span>";
      break;
      case "COMPLETE":
        return "<span class='label label-success'>COMPLETE</span>";
      break;
      case "CANCELLED":
        return "<span class='label label-danger'>CANCELLED</span>";
      break;
      case "CLOSED":
        return "<span class='label label-danger'>CLOSED</span>";
      break;
    }
};



var m_names = new Array("January", "February", "March", 
"April", "May", "June", "July", "August", "September", 
"October", "November", "December");

Date.prototype.toFormattedString = function (f)
{
    var nm = this.getMonthName();
    var nd = this.getDayName();
    f = f.replace(/yyyy/g, this.getFullYear());
    f = f.replace(/yy/g, String(this.getFullYear()).substr(2,2));
    f = f.replace(/MMM/g, nm.substr(0,3).toUpperCase());
    f = f.replace(/Mmm/g, nm.substr(0,3));
    f = f.replace(/MM\*/g, nm.toUpperCase());
    f = f.replace(/Mm\*/g, nm);
    f = f.replace(/mm/g, String(this.getMonth()+1).padLeft('0',2));
    f = f.replace(/DDD/g, nd.substr(0,3).toUpperCase());
    f = f.replace(/Ddd/g, nd.substr(0,3));
    f = f.replace(/DD\*/g, nd.toUpperCase());
    f = f.replace(/Dd\*/g, nd);
    f = f.replace(/dd/g, String(this.getDate()).padLeft('0',2));
    f = f.replace(/d\*/g, this.getDate());
    return f;
};

Date.prototype.getMonthName = function ()
{
    return this.toLocaleString().replace(/[^a-z]/gi,'');
};

//n.b. this is sooo not i18n safe :)
Date.prototype.getDayName = function ()
{
    switch(this.getDay())
    {
      case 0: return 'Sunday';
      case 1: return 'Monday';
      case 2: return 'Tuesday';
      case 3: return 'Wednesday';
      case 4: return 'Thursday';
      case 5: return 'Friday';
      case 6: return 'Saturday';
    }
};

String.prototype.padLeft = function (value, size) 
{
    var x = this;
    while (x.length < size) {x = value + x;}
    return x;
};


function pad_new(n,width,z){
  z = z || '0';
  n = n + '';
  return n.length >= width ? n : new Array(width - n.length + 1).join(z)+ n;
}


function pad(str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}

function discounted(value,percentage){
   var percentage = parseFloat(percentage/100);
   var dis_value  = parseFloat(value) *  parseFloat(percentage);
   var obj = {
        result : value - dis_value,
        dis_value : dis_value,
   };   
   return obj;
   
}


$(function(){

    $('body').on('keyup','.numbers_only',function(){
       console.log($(this).val());
       this.value = this.value.replace(/[^0-9.]/g,'');
       /*this.value = this.value.replace(/^\./g,'');*/
    });

    $('body').on('blur','.pad',function(){
       var max = $(this).attr('data-max-pad');
       
       max = (typeof(max)=="undefined")? 6: max ; 
       this.value = pad(this.value,max);
    });

    $('body').on('keyup','.uppercase',function(){
         this.value = this.value.toUpperCase();
    });

    $('body').on('keyup','.comma',function(){
       this.value = comma(this.value);
    });


    $('.comma').keyup(function(){
      this.value = comma(this.value);
    })
           

});



var datatable_option = {
	"sPaginationType": "full_numbers",
	"sDom" : "<'row t-head'<'col-md-5 form-inline'f><'col-md-5 data_option form-inline'><'test'r>>t<'row row-content'<'col-md-5'i><'col-md-7'p>>",  
  "bDestroy": true,
}

var datatable_option1 = {
  "sPaginationType": "full_numbers",
  "sDom": "<'row t-head'<'col-xs-5 form-inline'><'col-xs-5 data_option form-inline'><'test'r>>t<'row row-content'<'col-md-5'i><'col-md-7 paginate'p>>",  
  "bSort": false
}

var datatable_option2 = {
  "sPaginationType": "full_numbers",
  "sDom": "<'row t-head'<'col-xs-5 form-inline'><'col-xs-5 data_option form-inline'><'test'r>>t<'row row-content'<'col-md-5'i><'col-md-7 paginate'p>>",    
}


var datatable_option_scroll = {
  "sPaginationType": "full_numbers",
  "sDom": "<'row t-head'T<'col-md-5 form-inline'f><'col-md-5 data_option form-inline'><'test'r>><'data-scroll't><'row row-content'<'col-md-5'i><'col-md-7'p>>",
  "aaSorting": [[ 1, "asc" ]]
}

$(function(){

    $('#apply_filter').click(function(){
        $(this).addClass('disabled');
    });

});

var utils = {
  store : function(namespace, data){
    if(arguments.length > 1){
      return localStorage.setItem(namespace,JSON.stringify(data))
    }else{
      var store = localStorage.getItem(namespace);
      return (store && JSON.parse(store)) || [];
    }
  }  
}





var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

