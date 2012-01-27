var http = require('http');
var url = require('url');
var net = require('net');
var responder = null;

var uploadListener = http.createServer(
                       function (request, response)
                       {
                         var pu = url.parse(request.url, true);
                         if (pu.query.fileId != undefined)
                         {
                           setTimeout(
                             function ()
                             {
                               responder.write(pu.query.fileId + "\n");
                             }
                             , 10000);
                         }

                         response.writeHead(200, {'Content-Type': 'text/plain'});
                         response.end('OK\n');
                       }
    );

var doneListener = net.createServer(
                       function (stream)
                       {
                         stream.setEncoding('utf8');
                         responder = stream;
                       }
                     );
                                                                                                                                         

uploadListener.listen(8124);
doneListener.listen(8125);