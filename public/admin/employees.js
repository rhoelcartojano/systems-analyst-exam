jQuery(function() {

  // Get departments then show modal
  jQuery("#createEmployeeModalShow").on('click', function (e) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
    });
    e.preventDefault();
  
    $.ajax({
      type: 'GET',
      url: '/department',
      success: function (data) {
        console.log('Successfully fetched.');
        
        $.each(data.body, function (key, value)  { 
          $('#selectDepartment').append("<option value="+value.id+">"+value.name+"</option>");
        })

        $("#staticBackdropCreateEmployee").modal('show');
      },
      error: function (data) {
        if(data.status == 422) {
          var error = JSON.parse(data.responseText)
          alert(error.message)
        }
        console.error('error', data)
      }
    });
  });

  jQuery("#createEmployeeBtn").on('click', function (e) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
    });
    e.preventDefault();

    var access = [];
    if(jQuery('#chxAdd:checked').val() === 'on') access.push('Add'); 
    if(jQuery('#chxEdit:checked').val() === 'on') access.push('Edit'); 
    if(jQuery('#chxDelete:checked').val() === 'on') access.push('Delete'); 
    if(jQuery('#chxView:checked').val() === 'on') access.push('View'); 
    if(jQuery('#chxSearch:checked').val() === 'on') access.push('Search'); 
    if(jQuery('#chxPrint:checked').val() === 'on') access.push('Print'); 
    if(jQuery('#chxExport:checked').val() === 'on') access.push('Export'); 

    const formData = {
      department_id: jQuery('#selectDepartment').val(),
      name: jQuery('#createEmployeeName').val(),
      email: jQuery('#createEmployeeEmail').val(),
      password: Math.random().toString(36).slice(2, 10),
      access_utilities: JSON.stringify(access)
    };

    console.log('pass: '+formData.password)
  
    $.ajax({
      type: 'POST',
      url: '/employee',
      data: formData,
      dataType: 'json',
      success: function (data) {
        console.log('Employee successfully created.');
        $('#staticBackdropCreateEmployee').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').removeAttr("style");
        employeesList();

        $("#staticBackdropShowEmployeeCredentials").modal('show');
        $('#employeeUsername').html(formData.email);
        $('#employeePassword').html(formData.password);
       
      },
      error: function (data) {
        if(data.status == 422) {
          var error = JSON.parse(data.responseText)
          alert(error.message)
        }
        console.error('error', data)
      }
    });
    return false;
  });

  jQuery("#updateEmployeeBtn").click( function() {

    var access = [];
    if(jQuery('#chxEditAdd:checked').val() === 'on') access.push('Add'); 
    if(jQuery('#chxEditEdit:checked').val() === 'on') access.push('Edit'); 
    if(jQuery('#chxEditDelete:checked').val() === 'on') access.push('Delete'); 
    if(jQuery('#chxEditView:checked').val() === 'on') access.push('View'); 
    if(jQuery('#chxEditSearch:checked').val() === 'on') access.push('Search'); 
    if(jQuery('#chxEditPrint:checked').val() === 'on') access.push('Print'); 
    if(jQuery('#chxEditExport:checked').val() === 'on') access.push('Export');
    
    const formData = {
      id: jQuery('#employeeId').val(),
      name: jQuery('#updateEmployeeName').val(),
      department_id: jQuery('#updateEmployeeDepartment').val(),
      access_utilities: JSON.stringify(access)
    };

    $.ajax({
      type: 'PUT',
      url: '/employee/'+formData.id,
      data: formData,
      dataType: 'json',
      success: function (data) {
        console.log('Successfully updated.');
        $('#staticBackdropUpdateEmployee').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').removeAttr("style");
        
        employeesList();
      },
      error: function (data) {
        if(data.status == 422) {
          var error = JSON.parse(data.responseText)
          alert(error.message)
        }
        console.error('error', data)
      }
    });
    return false;
    
  });

  jQuery("#printEmployeeCredentialsBtn").click(function() {
    
    $('#staticBackdropShowEmployeeCredentials').modal('hide');
    $('#staticBackdropUpdateEmployee').modal('hide');
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open').removeAttr("style");

    var printContents = document.getElementById("printArea").innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
  });
});

const employeesList = () => {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
    }
  });
  
  $.ajax({
    type: 'GET',
    url: '/employee',
    success: function (data) {
      
      if(data.body[0]){

        $('#employeesList').empty();
        
        $.each(data.body, function (key, value)  { 
          const access = JSON.parse(value.access_utility['access_utilities'])
          $('#employeesList').append("<tr>\
            <th scope='row' class='row-department-name'>"+value.department['name']+"</th>\
            <td class='row-name'>"+value.name+"</td>\
            <td class='row-access-utilities'>"+
              $.each(access, function (value)  { 
                "<span class='badge text-bg-light'>"+value+"</span>"
              })
            +"</td>\
            <td>\
              <button type='button' id="+value.id+" onClick='editEmployee(this.id)' class='btn btn-primary btn-sm'>Update</button>\
              <button type='button' id="+value.id+" onClick='deleteEmployee(this.id)' class='btn btn-danger btn-sm'>Delete</button>\
            </td>\
            </tr>"
          );
        })
      } else {
        $('#employeesList').empty();
        $('#employeesList').append("<td colspan='4'><br><span class='text-center'>No employees found. Please create...</span></td>");
      }
    },
    error: function (data) {
      if(data.status == 422) {
        var error = JSON.parse(data.responseText)
        alert(error.message)
      }

      console.error('error', data)
    }
  });
  return false;
} 

const editEmployee = (id) => {
  $.ajax({
    type: 'GET',
    url: '/employee/'+id+'/edit',
    success: function (data) {
      const department = data.body[0].department;
      const name = data.body[0].name;
      const access = JSON.parse(data.body[0].access_utility['access_utilities']);

      $.each(access, function (key, value)  { 
        switch (value) {
          case "Add":
            $('#chxEditAdd').prop('checked', true);
            break;
          case "Edit":
            $('#chxEditEdit').prop('checked', true);
            break;
          case "Delete":
            $('#chxEditDelete').prop('checked', true);
            break;
          case "View":
            $('#chxEditView').prop('checked', true);
            break;
          case "Search":
            $('#chxEditSearch').prop('checked', true);
            break;
          case "Print":
            $('#chxEditPrint').prop('checked', true);
            break;
          case "Export":
            $('#chxEditExport').prop('checked', true);
            break;
          default:
            break;
        }
      });

      $('#updateEmployeeDepartment').find('option').remove();

      $('#updateEmployeeDepartment').append("<option value="+department['id']+" selected>"+department['name']+"</option>");
      $.each(data.departments, function (key, value)  { 
        $('#updateEmployeeDepartment').append("<option value="+value.id+">"+value.name+"</option>");
      })
      
      $('#updateEmployeeName').val(name);
      $('#employeeId').val(id);

      if(jQuery('#chxCreate:checked').val() === 'on') access.push('Create'); 
      if(jQuery('#chxUpdate:checked').val() === 'on') access.push('Update'); 
      if(jQuery('#chxDelete:checked').val() === 'on') access.push('Delete'); 
      if(jQuery('#chxSearch:checked').val() === 'on') access.push('Search'); 

      $("#staticBackdropUpdateEmployee").modal('show');
    },
    error: function (data) {
      if(data.status == 422) {
        var error = JSON.parse(data.responseText)
        alert(error.message)
      }
      console.error('error', data)
    }
  });
}

const deleteEmployee = (id) => {
  if (confirm("Are you sure?") == true) {
    $.ajax({
      type: 'DELETE',
      url: '/employee/'+id+'',
      success: function (data) {
        alert('Successfully deleted.');
        employeesList();
      },
      error: function (data) {
        if(data.status == 422) {
          var error = JSON.parse(data.responseText)
          alert(error.message)
        }
        console.error('error', data)
      }
    });
  } 
}

$(window).load(function() {
  employeesList();
});