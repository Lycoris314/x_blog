onload=()=>{
    document.querySelector("#back").onclick=()=>{
        history.back();
    }
    document.querySelector("#submit").onclick=()=>{
        location="system/system_account_edit_exe.php";
    }
}