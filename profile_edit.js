onload=()=>{

    document.querySelector("#upfile").onchange=()=>{
        let reader= new FileReader();
        reader.readAsDataURL(document.querySelector("#upfile").files[0]);
        //setTimeout(()=>{console.log(reader.result)},1000);
        reader.onload=()=>{
            document.querySelector("img").src=reader.result;
            //console.log(reader.result);
        };
        
    };
};