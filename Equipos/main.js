const search = () =>{
    const searchbox = document.getElementById("search-item").value.toUpperCase();
    const contenedor = document.getElementById("g_container")
    const minicarta = document.querySelectorAll(".minicarta")
    const card = document.querySelectorAll(".minicarta > img")
    const nombres = contenedor.getElementsByTagName("h2")
  
    for(var i=0; i < nombres.length; i++){
        let match = minicarta[i].getElementsByTagName('h2')[0];
  
        if(match){
  
            let textValue = match.textContent || match.innerHTML
  
            if(textValue.toUpperCase().indexOf(searchbox) > -1){
              card[i].style.display = "flex";
              
     
            }else{
              card[i].style.display = "none";

            }
        }
    }
  }