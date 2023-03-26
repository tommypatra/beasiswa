// Function exec ajax
function appAjaxUpload(vurl, vdata, vtype="post", vtimeout=5000,vasync = true) {
  return $.ajax({
    url: vurl,
    data: vdata,
    processData: false,
    contentType: false,    
    timeout: vtimeout,
    type: vtype,
    dataType: "json",
    async: vasync,
    //dataType: "script",
    success: function (vRet) {
      vretval = vRet;
    },
    error: function (request, status, error) {
      var errors = $.parseJSON(request.responseText);
      jQuery.each( errors['errors'], function( key, value ) {
        let tmppesan="";
        jQuery.each( value, function( i, msg ) {
          tmppesan=tmppesan+" "+msg;
        });
        let configs = {
              title:"Terjadi Kesalahan",
              message:tmppesan,
              status: TOAST_STATUS.DANGER,
              timeout: 5000
        }
        Toast.create(configs);          
      });     
    },
  });
}

// Function exec ajax
function appAjax(vurl, vdata, vtype="post", vtimeout=5000,vasync = true) {
  return $.ajax({
    url: vurl,
    data: vdata,
    timeout: vtimeout,
    type: vtype,
    dataType: "json",
    async: vasync,
    //dataType: "script",
    success: function (vRet) {
      vretval = vRet;
    },
    error: function (request, status, error) {
      var errors = $.parseJSON(request.responseText);
      jQuery.each( errors['errors'], function( key, value ) {
        let tmppesan="";
        jQuery.each( value, function( i, msg ) {
          tmppesan=tmppesan+" "+msg;
        });
        let configs = {
              title:"Terjadi Kesalahan",
              message:tmppesan,
              status: TOAST_STATUS.DANGER,
              timeout: 5000
        }
        Toast.create(configs);          
      });     
    },
  });
}

function showmymessage(pmessages=["tidak ada"],pstatus=true,pwaktu=5000){
  jQuery.each( pmessages, function( key, value ) {
    let confsts=TOAST_STATUS.INFO;
    let vtitle="Web Info";
    if(pstatus){
      confsts=TOAST_STATUS.SUCCESS;
      vtitle="Berhasil";
    }else{
      confsts=TOAST_STATUS.DANGER;
      vtitle="Terjadi Kesalahan";
    }
    let configs = {
          title: vtitle,
          message: value,
          status: confsts,
          timeout: pwaktu
    }
    Toast.create(configs);          
  });     
}

$(".action-url").click(function(){
  let vurl=$(this).data('url');
  if(confirm('apakah anda yakin?')){
    window.location.replace(vurl);
  }
});

function retvalue(vparam){
  if(vparam === null){
    vparam='';
  }
  return vparam;
}

function replaceNull(someObj, replaceValue = "") {
  const replacer = (key, value) => 
    String(value) === "null" || String(value) === "undefined" ? replaceValue : value; 
  return JSON.parse( JSON.stringify(someObj, replacer));
}