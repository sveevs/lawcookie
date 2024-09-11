const cookieEl = document.querySelector('.cookie-block');
const okEl = document.querySelector('.ok');

okEl.addEventListener('click', () => {
  cookieEl.style.display = 'none';
  
   Cookies.set('hide-cookie', 'true', {
   expires: 30
  });  
  
});                                                                                                                                                                                                            

let cookies = () => {
  if (Cookies.get('hide-cookie')) {
      cookieEl.style.display = 'none';
  }

  

}


cookies();