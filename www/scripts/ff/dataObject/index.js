$(document).ready(
  function ()
  {
    
    jQuery.fn.bindAll = function(options) {
      var $this = this;
      jQuery.each(options, function(key, val){
        $this.bind(key, val);
      });
      return this;
    }
    
    var imageListeners = {
      swfuploadLoaded: function(event)
      {

      },
      fileQueued: function(event, file)
      {
        $(this).swfupload('startUpload');
      },
      fileQueueError: function(event, file, errorCode, message)
      {
        
      },
      fileDialogStart: function(event)
      {
        //
      },
      fileDialogComplete: function(event, numFilesSelected, numFilesQueued)
      {

      },
      uploadStart: function(event, file)
      {
        //
      },
      uploadProgress: function(event, file, bytesLoaded)
      {
      
      },
      uploadSuccess: function(event, file, serverData)
      {
        var data = eval('(' + serverData + ')');
        $('#image' + this.id.substr(13)).attr('src', '/images/' + $('#objectName')[0].innerHTML + '/100/' + data.fileName.substr(0, 2) + '/' + data.fileName);
        $('#' + this.id.substr(13)).val(data.fileName);
      },
      uploadComplete: function(event, file)
      {
        $(this).swfupload('startUpload');
      },
      uploadError: function(event, file, errorCode, message)
      {
        
      }
    };

    $('.imageUploader').bindAll(imageListeners);
    
    $('.imageUploader').each(
      function()
      {
        $(this).swfupload({
          upload_url: '/admin/getFile/',
          file_size_limit : '0',
          file_types : '*.jpg;*.png;*.gif;',
          file_types_description : 'Изображения',
          file_upload_limit : '0',
          flash_url : '/scripts/ff/swfupload/swfupload.swf',
          button_image_url : '/scripts/ff/swfupload/uploadButton.png',
          button_width : 60,
          button_height : 18,
          button_placeholder : $('#imageButton' + this.id.substr(13))[0],
          post_params:
          {
            'type' : 'images',
            'objectName' : $('#objectName')[0].innerHTML,
            'propertyName' : this.title
          }
        })
      }
    );
    
    var fileListeners = {
      swfuploadLoaded: function(event)
      {

      },
      fileQueued: function(event, file)
      {
        $(this).swfupload('startUpload');
      },
      fileQueueError: function(event, file, errorCode, message)
      {
        
      },
      fileDialogStart: function(event)
      {
        //
      },
      fileDialogComplete: function(event, numFilesSelected, numFilesQueued)
      {

      },
      uploadStart: function(event, file)
      {
        //
      },
      uploadProgress: function(event, file, bytesLoaded)
      {
      
      },
      uploadSuccess: function(event, file, serverData)
      {
        var data = eval('(' + serverData + ')');
        $('#' + this.id.substr(13)).val(data.fileName);
      },
      uploadComplete: function(event, file)
      {
        $(this).swfupload('startUpload');
      },
      uploadError: function(event, file, errorCode, message)
      {
        
      }
    };

    $('.fileUploader').bindAll(fileListeners);
    
    $('.fileUploader').each(
      function()
      {
        $(this).swfupload({
          upload_url: '/admin/getFile/',
          file_size_limit : '0',
          file_types : '*.*',
          file_types_description : 'Изображения',
          file_upload_limit : '0',
          flash_url : '/scripts/ff/swfupload/swfupload.swf',
          button_image_url : '/scripts/ff/swfupload/uploadButton.png',
          button_width : 60,
          button_height : 18,
          button_placeholder : $('#fileButton' + this.id.substr(12))[0],
          post_params:
          {
            'type' : 'files',
            'objectName' : $('#objectName')[0].innerHTML,
            'propertyName' : this.title
          }
        })
      }
    );
    
    $('img.image').each(
      function()
      {
        if (this.src == '' || this.src == window.location)
        {
          this.src = '/scripts/ff/swfupload/empty.png';
        }
        else
        {
          var fileName = this.src.substr(-32);
          this.src = '/images/' + $('#objectName')[0].innerHTML + '/admin/' + fileName.substr(0, 2) + '/' + fileName;
        }
      }
    );
    
  }
);
