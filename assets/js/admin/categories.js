'use strict'

require('datatables.net-bs4')
require('datatables.net-fixedheader')

let currentDataTable = null

$(document).ready(() => {
  $('#check-all').change(() => {
    $('input[type=checkbox]').prop('checked', $('#check-all').prop('checked'))
  })

  table()
})

global.table = () => {
  if (currentDataTable != null) {
    destroyDatatable()
  }

  currentDataTable = $('#categories').DataTable({
    'order': [[0, 'desc'], [3, 'desc'], [4, 'desc']],
    'columnDefs': [{
      'targets': [5],
      'orderable': false,
      'searchable': false
    }],
    iDisplayLength: 15,
    'language': {
      'url': '/lang/fr.json'
    },
    fixedHeader: {
      header: true,
      headerOffset: 45
    }
  })

  currentDataTable.on('init.dt', () => {})
}

function destroyDatatable () {
  currentDataTable.destroy()
}
