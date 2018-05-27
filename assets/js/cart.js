require('sweetalert')
require('datatables.net-bs4')
require('datatables.net-fixedheader')

$(document).ready(function () {
  $('a').click(function (e) {
    let element = $(e.target)

    if (element.attr('data-href')) {
      e.preventDefault()

      const productSku = element.data('id')

      let quantity = $('#quantity-product-' + productSku).find(':selected').val()

      $.ajax({
        url: element.data('href'),
        data: 'quantity=' + quantity,
        success: function () {
          swal({
            title: 'Produit ajouté au panier',
            icon: 'success'
          })
        },
        fail: function () {
          swal({
            title: 'Echec de l\'ajout',
            text: 'L\'ajout à échoué, assurez vous que la quantitée indiqué est supérieure ou égale à 1',
            icon: 'error'
          })
        }
      })
    }
  })
})

$(document).ready(function () {
  $('#cart').on('init.dt', () => {}).DataTable({
    'order': [[0, 'desc']],
    'columnDefs': [{
      'targets': [4],
      'orderable': false,
      'searchable': false
    }],
    iDisplayLength: 10,
    'language': {
      'url': '/lang/fr.json'
    },
    searching: false,
    paging: false,
    fixedHeader: {
      footer: true,
      footerOffset: 0,
      header: true,
      headerOffset: 0
    }
  })
  calculateSum()
})

$(document).ready(function () {
  $('.close1').on('click', function () {
    $('.cart-header').fadeOut('slow', function () {
      $('.cart-header').remove()
      calculateSum()
    })
  })

  $('.close2').on('click', function () {
    $('.cart-header1').fadeOut('slow', function () {
      $('.cart-header1').remove()
      calculateSum()
    })
  })
})

function calculateSum() {
  var sum = 0
  $(".price-ht").each(function() {
    var value = $(this).text()

    if (!isNaN(value) && value.length != 0) {
      sum += parseFloat(value)
    }
  })
  var vat = sum * 20 / 100
  $('.total-price-ht').html(Math.floor(sum * 100) / 100)
  $('.vat').html(Math.floor(vat * 100) / 100)
  $('.total-with-tax').html(Math.floor((vat + sum) * 100) / 100)
}
