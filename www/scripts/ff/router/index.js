$(document).ready(
  function ()
  {
    $('a.addParameter').click(
      function()
      {
        var index = $(this).attr('data-index');
        $('#parameters_' + index + ' tr#addLink').before('<tr><td>Имя</td><td><input type="text" name="routeParameterName[' + index + '][]" value="" /></td></tr><tr><td>Значение</td><td><select name="routeParameterType[' + index + '][]"><option selected="selected" value="2">Целое число</option><option  value="3">Число с плавающей точкой</option><option  value="4">Дата и/или время</option><option  value="1">Текст</option><option  value="9">Длинный текст</option><option  value="6">Пароль</option><option  value="7">Картинка</option><option  value="8">Файл</option></select></td></tr>');
        return false;
      }
    );
  }
);