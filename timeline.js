$(()=>{
    $(".del").on("click",(e)=>{
        if(!confirm("本当に削除してよろしいですか？")){
            e.preventDefault();
        }
    })
})