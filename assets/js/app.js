document.addEventListener("DOMContentLoaded", function(){
  document.querySelectorAll(".accordion-toggle").forEach(btn=>{
    btn.addEventListener("click",()=>{
      let content = btn.nextElementSibling;
      content.style.display = content.style.display === "block" ? "none" : "block";
    });
  });
});
