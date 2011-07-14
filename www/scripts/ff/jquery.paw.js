 jQuery.fn.extend({
   buttonEnable: function()
   { 
     $(this).attr('data-enabled', 'enabled');
     $(this).buttonUp();
     $(this).mouseover($(this).buttonOver);
     $(this).mousedown($(this).buttonDown);
     $(this).mouseup($(this).buttonUp);
     $(this).mouseout($(this).buttonUp);
   },
   buttonDisable: function()
   {
     $(this).attr('data-enabled', 'disabled');
     $(this).buttonDisabled();
   },
   buttonIsEnabled: function()
   {
     if ($(this).attr('data-enabled') == 'enabled')
     {
       return true;
     }
     else
     {
       return false;
     }
   },
   buttonUp: function()
   {
     if ($(this).buttonIsEnabled())
     {
       $(this).css('background-position', '0px 0px');
     }
   },
   buttonOver: function()
   {
     if ($(this).buttonIsEnabled())
     {
       $(this).css('background-position', '0px -' + $(this).height() + 'px');
     }
   },
   buttonDown: function()
   {
     if ($(this).buttonIsEnabled())
     {
       $(this).css('background-position', '0px -' + $(this).height() * 2 + 'px');
     }
   },
   buttonDisabled: function()
   {
     if (!$(this).buttonIsEnabled())
     {
       $(this).css('background-position', '0px -' + $(this).height() * 3 + 'px');
     }
   }
 });
 
 $.declineNumber = function(value, strings)        
 {
   if(value > 100) {
     value = value % 100;
   }
  
   firstDigit = value % 10;
   secondDigit = Math.floor(value / 10);
  
   if (secondDigit != 1) {
     if (firstDigit == 1) {
       return strings[0];
     } else if (firstDigit > 1 && firstDigit < 5) {
       return strings[1];
     } else {
       return strings[2];
     }
   } else {
     return strings[2];
   }
 };
 
  /**
  * Applies data to template
  * @param {String} template Template as used for server-side of P@W
  * @param {Array} data Array of objects with properties named in respect to template. 
  *   {$userName} substitutes to the value of data.userName
  * @return {String} html to append to DOM
  */                      
  $.applyTemplate = function(template, data)
  {
    var result = '';
    
    var vars = template.match(/{\$[^}]+}/g);
    
    $.each(vars, function(key, variable) {
      vars[key] = variable.substr(2, variable.length - 3);
    });
    
    $.each(data, function(dummy, advert) {
      var item = template;
      
      $.each(vars, function(dummydummy, variable) {
        var pattern = new RegExp('{\\$' + variable + '}');
        item = item.replace(pattern, advert[variable]);
      });
      
      result += item;
    });
    
    return result;
  };