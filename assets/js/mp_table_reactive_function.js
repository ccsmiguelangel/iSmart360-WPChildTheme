function mp_table_reactive_click(inbound_localize_reactive_script) {
  const options = {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(inbound_localize_reactive_script)
  };

  fetch(inbound_localize_reactive_script.rest_url, options)
    .then(response => response.json())
    .catch(error => {
      console.error(error);
      console.error(error.message);
      console.error(error.stack);
    });
}

let reactiveSuscriptionBtn = document.getElementById('mp_link_reactive_element');
const textNoneInboundLabs = document.querySelector('.mp-no-subs');
textNoneInboundLabs.style.display = "none";
let tableSuscription = document.querySelector('.mp_wrapper_inbound_labs');
const ils = inbound_localize_reactive_script;
reactiveSuscriptionBtn.addEventListener('click', (e) =>{
  e.preventDefault(); 
  tableSuscription.innerHTML = 'Su suscripción fue reactivada correctamente.';
  mp_table_reactive_click(ils);

  setTimeout(() => { window.location.href = "https://ismart360.com/members/me/mp-membership/mp-payments/"; },  5000);

});
