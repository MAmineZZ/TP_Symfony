let removeButtons = [...document.querySelectorAll(".btn-danger")];

removeButtons.map((el) => {
    el.addEventListener("click", (e)=>{
        e.preventDefault();
        let link = e.currentTarget.getAttribute("href");
        let confirm = window.confirm("Do you want to confirm remove");
        if(confirm)  document.location.href = link;

    });
});