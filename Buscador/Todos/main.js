// BÚSQUEDA POR POSICIÓN

document.addEventListener('DOMContentLoaded', function() {
    const filtroBtns = document.querySelectorAll('.filtro-btn');
    const jugadores = document.querySelectorAll('.jugador');
    
    filtroBtns.forEach(function(btn) {
      btn.addEventListener('click', function() {
        const posicion = btn.dataset.posicion;
        
        jugadores.forEach(function(jugador) {
          if (posicion === 'todos' || jugador.dataset.posicion === posicion) {
            jugador.style.display = "flex";
            jugador.style.flexDirection = "column";
            jugador.style.alignItems = "center";
            jugador.style.justifyContent = "space-evenly";
          } else {
            jugador.style.display = "none";
          }
        });
      });
    });
  });

// SCROLL UP

document.getElementById("button-up").addEventListener("click", scrollUp);

function scrollUp(){

    var currentScroll = document.documentElement.scrollTop;

    if (currentScroll > 0){
        window.requestAnimationFrame(scrollUp);
        window.scrollTo (0, currentScroll - (currentScroll / 10));
        buttonUp.style.transform = "scale(0)";
    }
}

buttonUp = document.getElementById("button-up");

window.onscroll = function(){
    var scroll = document.documentElement.scrollTop;

    if (scroll > 200){
        buttonUp.style.transform = "scale(1)";
    }else if(scroll < 100){
        buttonUp.style.transform = "scale(0)";
    }
}


// BÚSQUEDA POR TEXTO

var accent_map = {'á':'a', 'é':'e', 'è':'e', 'í':'i','ó':'o','ú':'u','Á':'a', 'É':'e', 'è':'e', 'Í':'i','Ó':'o','Ú':'u'};
function accent_fold (s) {
  if (!s) { return ''; }
  var ret = '';
  for (var i = 0; i < s.length; i++) {
    ret += accent_map[s.charAt(i)] || s.charAt(i);
  }
  return ret;
};

const search = () =>{
  const searchbox = document.getElementById("search-item").value.toUpperCase();
  const contenedor = document.getElementById("jugadores")
  const minicarta = document.querySelectorAll(".minicarta")
  const nombres = contenedor.getElementsByTagName("h4")

  for(var i=0; i < nombres.length; i++){
      let match = minicarta[i].getElementsByTagName('h4')[0];

      if(match){

          let textValue = match.textContent || match.innerHTML

          if(textValue.toUpperCase().indexOf(searchbox) > -1){
            minicarta[i].style.display = "flex";
            minicarta[i].style.flexDirection = "column";
            minicarta[i].style.alignItems = "center";
            minicarta[i].style.justifyContent = "space-evenly";
   
          }else{
            minicarta[i].style.display = "none";
          }
      }
  }
}
