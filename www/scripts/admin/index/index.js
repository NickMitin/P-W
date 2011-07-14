var mode = 'login';

function setDefaultUserMode()
{
  $('#defaultUserBlock').show();   
}

function setAccessGrantedMode()
{
  $('#defaultUserBlock').hide();
  $('#accessGrantedBlock').show(); 
}

function setAccessDeniedMode(userId)
{
  $('#defaultUserBlock').hide();
  var sql = $('#sql').text();
  sql = sql.replace(/\#id/gi, userId);
  $('#sql').text(sql);
  $('#accessDeniedBlock').show();  
}

function showError(errorMessage)
{
  switch (errorMessage)
  {
    case 'wrongQueryType':
      
    break;
    case 'wrongAuthenticationData':
      showCommonError('Неверные e-mail или пароль');
    break;
    case 'userAlreadyExists':
      showEmailError('Пользователь с таким e-mail уже зарегистрирован');
    break;
    case 'invalidEmail':
      showEmailError('Такого адреса не существует');
    break;
    case 'invalidPassword':
      showPasswordError('Пароль должен состоять хотя бы из одного символа');
    break;  
  }
}

function showEmailError($message)
{
  $('#' + mode + 'EmailComment').text($message);
  $('#' + mode + 'EmailComment').show();
}

function showPasswordError($message)
{
  $('#' + mode + 'PasswordComment').text($message);
  $('#' + mode + 'PasswordComment').show();
}

function showCommonError($message)
{
  $('#' + mode + 'Comment').text($message);
  $('#' + mode + 'CommentBar').show();
}

function hideErrors()
{
  $('#loginEmailComment').hide();
  $('#loginPasswordComment').hide();
  $('#loginCommentBar').hide();
  $('#registerEmailComment').hide();
  $('#registerPasswordComment').hide();
  $('#registerCommentBar').hide();
}

function login()
{
  hideErrors();
  
  var email = $('#login input[name=email]').val();
  var password = $('#login input[name=password]').val();
  
  $.ajax({
          url: '/admin/rp/',
          type: 'post',
          dataType: 'json',
          data: {'queryType': 'login',
                 'email': email,
                 'password': password
                },
          beforeSend: function(XMLHttpRequest)
          {
            
          },
          success: function(data, textStatus, XMLHttpRequest)
          {
            switch (data.answer)
            {
              case 'accessGranted':
                setAccessGrantedMode();
              break;
              case 'accessDenied':
                setAccessDeniedMode(data.userId);
              break;
              case 'error':
              {
                showError(data.errorMessage);
              }
            }
          },
          error: function(data, textStatus, XMLHttpRequest){
            
          }
        });
  
}

function register()
{
  hideErrors();
  
  var email = $('#register input[name=email]').val();
  var password = $('#register input[name=password]').val();
  
  $.ajax({
          url: '/admin/rp/',
          type: 'post',
          dataType: 'json',
          data: {'queryType': 'register',
                 'email': email,
                 'password': password
                },
          beforeSend: function(XMLHttpRequest)
          {
            
          },
          success: function(data, textStatus, XMLHttpRequest)
          {
            switch (data.answer)
            {
              case 'accessGranted':
                setAccessGrantedMode();  
              break;
              case 'accessDenied':
                setAccessDeniedMode(data.userId);  
              break;
              case 'error':
              {
                showError(data.errorMessage);
              }
            }
          },
          error: function(data, textStatus, XMLHttpRequest){
            
          }
        });  
}

$(document).ready(function() {
  $.ajax({
          url: '/admin/rp/',
          type: 'post',
          dataType: 'json',
          data: {'queryType': 'initialize'
                },
          beforeSend: function(XMLHttpRequest)
          {
            
          },
          success: function(data, textStatus, XMLHttpRequest)
          {
            switch (data.answer)
            {
              case 'defaultUser':
                setDefaultUserMode();
              break;
              case 'accessGranted':
                setAccessGrantedMode();
              break;
              case 'accessDenied':
                setAccessDeniedMode(data.userId);
              break;
            }
          },
          error: function(data, textStatus, XMLHttpRequest){
            
          }
        });
        
  $('#toggleRegister').click(function() {
    mode = 'register';
    $('#login').hide();
    $('#register').show();
  });
  
  $('#toggleLogin').click(function() {
    mode = 'login';
    $('#register').hide();
    $('#login').show();   
  });
  
  $('#loginButton').click(function() {
    login();
  });
  
  $('#registerButton').click(function() {
    register();
  });
})