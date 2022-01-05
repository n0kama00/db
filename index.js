/**
 * Haetaan tavitut elementit domista
 */
 var form = document.querySelector("form");
 var loginResult = document.querySelector("p")
 var resourceElem = document.getElementById("resource")
 
 /**
  * Lisätään napeille kuuntelijafunktiot
  */
 document.querySelector("button").addEventListener("click", getResource)
 form.addEventListener("submit", login)
 
 /**
  * Login tietojen lähetys
  */
 function login(e){
     e.preventDefault();
 
     //Muunnetaan form-data olioksi
     var data = new FormData(form)
 
     //base64 koodataan käyttäjän antamat käyttäjätunnus:salasana
     var base64cred = btoa( data.get("username")+":"+data.get("passwd") )
 
     //Luodaan basic auth otsikko ja muut parametrit
     //Authorization: Basic xxxxxxxxx
     var params = {
         headers: { 'Authorization':'Basic ' + base64cred },
         withCredentials: true,
         method: 'post'
     }
 
 
     fetch('http://localhost/pohja/login.php', params)
         .then(resp => resp.json())
         .then( data => {
             //Näytetään login tulos saadusta jsonista ja 
             //asetetaan saatu token session storageen talteen
             loginResult.textContent = data.info
             sessionStorage.setItem("token", data.token)            
         })
         .catch(e => {
             loginResult.textContent = "Failure!"
         })
         
 }
 
 /**
  * Haetaan tokenin avulla tietoja palvelimelta
  */
 function getResource(){
 
     //Listätään otsioihin Bearer token session storagesta
     var params = {
         headers: { 'authorization':'Bearer ' + sessionStorage.getItem("token") },
         withCredentials: true,
     }
 
     //Lähetetään pyyntö tokenin kanssa ja katsotaan saadanko vastaus.
     fetch('http://localhost/pohja/resources.php', params)
         .then(resp=>resp.json())
         .then(json=> resourceElem.textContent=json.message )
         .catch(e=>resourceElem.textContent="Virhe tuli pyynnössä.....")
 }