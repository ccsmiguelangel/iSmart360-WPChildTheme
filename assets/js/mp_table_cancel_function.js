console.log('hola mundo')

function mp_table_cancel_click(inbound_localize_script) {
  const options = {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(inbound_localize_script)
  };

  fetch(inbound_localize_script.rest_url, options)
    .then(response => response.json())
    .catch(error => {
      console.error(error);
      console.error(error.message);
      console.error(error.stack);
    });
}

let cancelSuscriptionBtn = document.getElementById('mp_table_cancel_element');
let tableSuscription = document.querySelector('.mp_wrapper');
const ils = inbound_localize_script;
cancelSuscriptionBtn.addEventListener('click', (e) =>{
  e.preventDefault();
  tableSuscription.innerHTML = 'Su suscripción fue cancelada correctamente.';
  mp_table_cancel_click(ils)
});
