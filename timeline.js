onload=()=>{


    document.querySelector("#delete").onclick=(e)=>{
        if(!confirm("本当に削除してよろしいですか？")){
            e.preventDefault();
        }
    }
}