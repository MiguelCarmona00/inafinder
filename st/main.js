// let header = document.getElementById("sts");
// let btns = document.getElementsByClassName("ejemplo");
// for (let i = 0; i < btns.length; i++) {
//   btns[i].addEventListener("click", function() {
//   let current = document.getElementsByClassName("active");
//   if (current.length > 0) { 
//     current[0].className = current[0].className.replace(" active", "");
//   }
//   this.className += " active";
//   });
// }

// const cartas = document.querySelectorAll('.ejemplo');

// cartas.forEach((actual)=>{
//   actual.addEventListener('click', ()=>{
//     const activo = document.querySelector('.active');

//     if(activo){
//       if(activo != actual){
//         activo.classList.remove('active');
//       }
//     }

//     if(!actual.className.includes('active')){
//       actual.classList.add('active');
//     }else{
//       actual.classList.remove('active')
//     }
//   });
// });

const containers = document.querySelectorAll('.ejemplo');

containers.forEach((container) => {
  container.addEventListener('click', () => {
    const boxes = container.querySelectorAll('.jugadores');
    boxes.forEach((jugadores) => {
      if (jugadores.classList.contains('show')) 
      {
        jugadores.classList.remove('show');
      } else {
        jugadores.classList.add('show');
      }
    });
  });
});


// FILTRAR POR NOMBRE

const search = () =>{
  const searchbox = document.getElementById("search-item").value.toUpperCase();
  const contenedor = document.getElementById("sts")
  const minicarta = document.querySelectorAll(".ejemplo")
  const nombres = contenedor.getElementsByTagName("h3")

  for(var i=0; i < nombres.length; i++){
      let match = minicarta[i].getElementsByTagName('h3')[0];

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



// AFINIDAD

document.addEventListener('DOMContentLoaded', function() {
  const filtroBtns = document.querySelectorAll('.filtro-btn');
  const tecnicas = document.querySelectorAll('.ejemplo');
  
  filtroBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      const posicion = btn.dataset.posicion;
      
      tecnicas.forEach(function(ejemplo) {
        if (posicion === 'todos' || ejemplo.dataset.posicion === posicion) {
          ejemplo.style.display = "flex";
          ejemplo.style.flexDirection = "column";
          ejemplo.style.alignItems = "center";
          ejemplo.style.justifyContent = "space-evenly";
        } else {
          ejemplo.style.display = "none";
        }
      });
    });
  });
});