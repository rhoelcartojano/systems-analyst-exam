
jQuery(function() {

  // Create
  jQuery("#createDepartmentBtn").on('click', function (e) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
    });
    
    const formData = {
      name: jQuery('#name').val(),
      description: jQuery('#description').val(),
    };
  
    $.ajax({
      type: 'POST',
      url: '/department',
      data: formData,
      dataType: 'json',
      success: function (data) {
        console.log('Successfully created.');
        $('#staticBackdrop').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').removeAttr('style');
        
        departmentsList();
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

  jQuery("#updateDepartmentBtn").click( function() {
    const formData = {
      id: jQuery('#departmentId').val(),
      name: jQuery('#updateName').val(),
      description: jQuery('#updateDescription').val(),
    };

    $.ajax({
      type: 'PUT',
      url: '/department/'+formData.id,
      data: formData,
      dataType: 'json',
      success: function (data) {
        console.log('Successfully updated.');
        $('#staticBackdropUpdate').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').removeAttr("style");
        departmentsList();
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
});

const departmentsList = () => {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
    }
  });
  
  $.ajax({
    type: 'GET',
    url: '/department',
    success: function (data) {

      if(data.body[0]){
        $('#departmentsList').empty();

        $.each(data.body, function (key, value)  { 
          $('#departmentsList').append("<tr>\
            <th scope='row' class='row-name'>"+value.name+"</th>\
            <td class='row-description'>"+value.description+"</td>\
            <td>\
              <button type='button' id="+value.id+" onClick='editDepartment(this.id)' class='btn btn-primary btn-sm'>Update</button>\
              <button type='button' id="+value.id+" onClick='deleteDepartment(this.id)' class='btn btn-danger btn-sm'>Delete</button>\
            </td>\
            </tr>"
          );
        })
      } else {
        $('#departmentsList').empty();
        $('#departmentsList').append("<td colspan='3'><br><span class='text-center'>No departments found. Please create...</span></td>");
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

const editDepartment = (id) => {
  $.ajax({
    type: 'GET',
    url: '/department/'+id+'/edit',
    success: function (data) {
      const name = data.body[0].name;
      const description = data.body[0].description;
      
      $("#updateName").val(name);
      $("#updateDescription").val(description);
      $("#departmentId").val(id);
      $("#staticBackdropUpdate").modal('show');
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

const deleteDepartment = (id) => {
  if (confirm("Are you sure?") == true) {
    $.ajax({
      type: 'DELETE',
      url: '/department/'+id+'',
      success: function (data) {
      alert('Successfully deleted.');
      departmentsList();
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
  departmentsList();
});
