$(document).on("click","#btnRefreshBranch",function(){
    $("#local-branches-area").load('fetchLocalBranch');
});

$(document).on('click','#btnRunSync',function(){
    $("#sync-result").text($("#sync-result").text()+'Running sync: git pull origin '+ $("#Sync-target").val()+'\n...');
    $.post("runSync",
        {
            sync_target_folder:$("#Sync-target").val()
        },
        function(data,status){
            $("#sync-result").text($("#sync-result").text()+'\nstatus:'+status+'\n'+data+'\n');
        });
});

$(document).on('click','#btnCheckout',function(){
    $("#checkout-result").text($("#checkout-result").text()+'Running checkout: git checkout '+ $("#Switch-to").val()+'\n...');
    $.post("runCheckout",
        {
            sync_target_folder:$("#Switch-to").val()
        },
        function(data,status){
            $("#checkout-result").text($("#checkout-result").text()+'\nstatus:'+status+'\n'+data+'\n');
        });
});

$(document).on('click','#btnConfirmCleanClone',function(){
    $("#modalClean").modal("hide");
    $("#cleanclone-result").text($("#checkout-result").text()+'Cleaning folder...'+'\n...');
    $.post("cleanFolder",
        {
            sync_target_folder:$("#Switch-to").val()
        },
        function(data,status){
            $("#checkout-result").text($("#checkout-result").text()+'\nstatus:'+status+'\n'+data+'\n');
        });
    $("#cleanclone-result").text($("#cleanclone-result").text()+'Cloneing...'+'\n...');
    $.post("cloneRepo",
        {
            url:"null"
        },
        function(data,status){
            $("#cleanclone-result").text($("#cleanclone-result").text()+'\nstatus:'+status+'\n'+data+'\n'+'Operation ends\nPlease refresh this page.');

        });
});