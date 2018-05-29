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

  currentDataTable = $('#products').DataTable({
    'order': [[0, 'asc'], [1, 'asc'], [7, 'desc'], [8, 'desc']],
    'columnDefs': [{
      'targets': [9],
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
