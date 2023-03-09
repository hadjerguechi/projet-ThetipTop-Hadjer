/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
const Swal = require('sweetalert2');

// Importing jquery
var $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;

// start the Stimulus application
//import './bootstrap';
require("bootstrap");

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});

var $main_window = $(window);

// Handle scroll navbar

$main_window.on("scroll", function () {

    if ($(this).scrollTop() > 2) {

        $(".header-wrap").addClass('fixed');
        $(".link").addClass("link-black");

    } else {
        
        $(".header-wrap").removeClass('fixed');
        $(".link").removeClass("link-black");
    }

});

/*************************header button logout****************/
$('#compte').click(function() {
    $("#deconnexion").toggleClass('hide');
});

/* Navbar Toggler ***********************************************/
$(document).scroll(function () {
    var $nav = $(".navbar-expand-xl");
    $nav.toggleClass("scrolled", $(this).scrollTop() > $nav.height());
    $(".fixed-top").toggleClass("fixed", $(this).scrollTop() > $nav.height());
  });
  $("#nav-icon2").click(function () {
      console.log("je suis la");
    $(this).toggleClass("open");
  });


/***************Password eye*********************************/

    $('.password').on('input',function(e){
        let div = $(this).parent()
        let eye = div.find('.eye')
        if($(this).val().length  > 0){
            eye.removeClass('hide');
        }else {
            eye.addClass('hide');
        }
    });
    $('.eye').on('click', function() {
        $(this).each(function(){
            
            let password = $(this).parent().parent().find('.password')
             
             if ($(this).hasClass('fa-eye-slash')) {
                 $(this).removeClass('fa-eye-slash');
                 $(this).addClass('fa-eye');
                 password.get(0).type = 'text';
             } else {
                 $(this).removeClass('fa-eye');
                 $(this).addClass('fa-eye-slash');
                 password.get(0).type  = 'password';
             }
        })
         
         
     });
/************************Registration handler ********************/

  $('#form-register').on('submit', function(e) {
      e.preventDefault();

      let url = $('#form-register').attr('action');
    
      let lastname = $('#lastname').val();
      let firstname = $('#firstname').val();
      let email = $('#email').val();
      let password = $("#password").val();
      let address = $('#address').val();
    
    $('#text-register').addClass('hide');
    $('#loader-register').removeClass('hide');
    $('#btn-register').prop('disabled', true);

      fetch(url, {
          method: 'post',
          headers:{
              'Content-Type': 'application/json',
          },
          body: JSON.stringify({
              lastName : lastname,
              firstName : firstname,
              address : address,
              email: email,
              password: password

          }),
      })
      .then((response) => {
          return response.json();
      })
      .then ((result) => {
          if (result.status == 'error'){
              let errorEmail = $(['<span class="form-error">' + result.message + '</span>'].join(''))
              if ($('#email-form-register').find('.form-error').length == 0) {
                $('#email-form-register').append(errorEmail)
              }
              
          }
          else{
            $('#confirmation-register').removeClass('hide');
            $('#content-register').addClass('hide');
            
          }
          $('#text-register').removeClass('hide');
          $('#loader-register').addClass('hide');
          $('#btn-register').prop('disabled', false);
          return result;
      })

  })

  /***********GET NUMBER CODE ***************************************/

  $("#getNumber").on("submit", function(e){
    e.preventDefault();
    let url = $("#getNumber").attr('action');
    let numberCode = $('#number').val();

    $('#text-register').addClass('hide');
    $('#loader-register').removeClass('hide');
    $('#btn-register').prop('disabled', true);

    fetch( url,{
        method: 'post',
          headers:{
              'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            numberCode : numberCode,

        })
    })
    
    .then( (response) => {
        return response.json();
    })
    .then( (result) => {

        if( result.status == "error"){
            let errorNumber = $(['<span class="form-error">' + result.message + '</span>'].join(''))
            if ($('#number-form-get').find('.form-error').length == 0) {
                $('#number-form-get').append(errorNumber)
            }
        }else{
            $('#confirmation-participated').removeClass('hide');
            $('#content-participated').addClass('hide');
            let number = $(['<span class="form-gif">' + result.number['title'] + '</span>'].join(''))
            if ($('#number-form-gif').find('.form-gif').length == 0) {
                $('#number-form-gif').append(number)
            }
        }
        $('#text-register').removeClass('hide');
        $('#loader-register').addClass('hide');
        $('#btn-register').prop('disabled', false);
        return result
    })
  })
/*************FORGET PASSWORD**************** */
  $('#forgetPassword').on('submit', function(e){
    e.preventDefault();
    let url = $('#forgetPassword').attr('action');
    
    let email = $('#emailforget').val();
    console.log(email);
    
    fetch(url ,{
        method :"post",
        headers : {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            email: email,

        })

    })
    .then( (response) => {
        return response.json();
    })
    .then( (result) => {
        if(result.status ="error"){
            let email = $(['<span class="form-error">' + result.message + '</span>'].join(''))
            if ($('#password-forget').find('.form-error').length == 0) {
                $('#password-forget').append(email)
            }

        }else{
            let email = $(['<span class="form-success">' + result.message + '</span>'].join(''))
            if ($('#password-forget').find('.form-success').length == 0) {
                $('#password-forget').append(email)
            }

        }

        return result

    })

  })
//iput show after clicking edit button
//     var firstNameInput = document.getElementById('firstNameInput');
//     var firstNameLabel = document.getElementById('firstNameLabel');
//     var lastNameInput = document.getElementById('lastNameInput');
//     var lastNameLabel = document.getElementById('lastNameLabel');
//     var birthDateInput = document.getElementById('birthDateInput');
//     var birthDateLabel = document.getElementById('birthDateLabel');
//     var adressInput = document.getElementById('adressInput');
//     var adressLabel = document.getElementById('adressLabel');
//     var emailInput = document.getElementById('emailInput');
//     var emailLabel = document.getElementById('emailLabel');
//     var numberInput = document.getElementById('numberInput');
//     var numberLabel = document.getElementById('numberLabel');
//     var editProfilBtn = document.getElementById('btn');
//     var iconButton = document.getElementById('button')

// $("#button").click(function() {
//     if(!firstNameLabel.style.display || firstNameLabel.style.display == "block"){
//         editProfilBtn.style.display = 'block';
//         iconButton.style.display = 'none'
//         firstNameLabel.style.display = 'none';
//         firstNameInput.style.display = 'block';
//         lastNameLabel.style.display = 'none';
//         lastNameInput.style.display = 'block';
//         birthDateLabel.style.display = 'none';
//         birthDateInput.style.display = 'block';
//         adressLabel.style.display = 'none';
//         adressInput.style.display = 'block';
//         emailLabel.style.display = 'none';
//         emailInput.style.display = 'block';
//         numberLabel.style.display = 'none';
//         numberInput.style.display = 'block';

//     }
//     else {
//         console.log(firstNameLabel.style.display)
//     } 
//   });


// $("#btn").click(function() {
//     editProfilBtn.style.display = 'none';
//     iconButton.style.display = 'block'
//     var firstName = $('#firstNameInput').val();
//     $('#firstNameLabel').text(firstName);
//     firstNameInput.style.display = 'none';
//     firstNameLabel.style.display = 'block';
//     var firstName = $('#lastNameInput').val();
//     $('#lastNameLabel').text(firstName);
//     lastNameInput.style.display = 'none';
//     lastNameLabel.style.display = 'block';
//     var firstName = $('#birthDateInput').val();
//     $('#birthDateLabel').text(firstName);
//     birthDateInput.style.display = 'none';
//     birthDateLabel.style.display = 'block';
//     var firstName = $('#adressInput').val();
//     $('#adressLabel').text(firstName);
//     adressInput.style.display = 'none';
//     adressLabel.style.display = 'block';
//     var firstName = $('#emailInput').val();
//     $('#emailLabel').text(firstName);
//     emailInput.style.display = 'none';
//     emailLabel.style.display = 'block';
//     var firstName = $('#numberInput').val();
//     $('#numberLabel').text(firstName);
//     numberInput.style.display = 'none';
//     numberLabel.style.display = 'block';
// })


  
  
    // var indirizzo = $('#indirizzoInput').val();
    // $('#defNomeSalone').text(firstName);
  
    // var cap = $('#capInput').val();
    // $('#defNomeSalone').append(cap);
  
    // var citta = $('#cittaInput').val();
    // $('#defNomeSalone').append(citta);
  
    // var provincia = $('#provinciaInput').val();
    // $('#defNomeSalone').append(provincia);
  
//   });
  
