@extends('admin.layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">

      <div class="card">
        <div class="card-header">{{ __('Dashboard') }}</div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            Welcome, Admin {{ Auth::user('admin')->name }}
        </div>
      </div>
      <br>

      <!--  Main content card -->
      <div class="card text-center">
        <div class="card-header">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="departments-tab" data-bs-toggle="tab" data-bs-target="#departments" type="button" role="tab" aria-controls="departments" aria-selected="true">
                {{ __('Departments') }}
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="employees-tab" data-bs-toggle="tab" data-bs-target="#employees" type="button" role="tab" aria-controls="employees" aria-selected="false">
                {{ __('Employees') }}
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab" aria-controls="reports" aria-selected="false">
                {{ __('Reports/Inquiry') }}
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">
                {{ __('Settings') }}
              </button>
            </li>
          </ul>

            <!-- Tab panes -->
          <div class="tab-content">

            <!-- start department-tab -->
            <div class="tab-pane active" id="departments" role="tabpanel" aria-labelledby="department-tab" tabindex="0">
              
              <div class="container">
                <div class="row">
                  <div class="col-md-12">
                    <button type="button" class="btn btn-primary btn-sm float-end mt-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                      {{ __('Create Department') }}
                    </button>
                  </div>
                </div>
              </div>
              <br>
              <div class="container">
                <div class="row">
                  <div class="col-md-12">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                        <th scope="col">Department Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody id="departmentsList">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>
            <!-- end department-tab -->

             <!-- start employees-tab -->
            <div class="tab-pane" id="employees" role="tabpanel" aria-labelledby="employees-tab" tabindex="0">

              <div class="container">
                <div class="row">
                  <div class="col-md-12">
                    <button type="button" id="createEmployeeModalShow" class="btn btn-primary btn-sm float-end mt-2">
                      {{ __('Create Employee') }}
                    </button>
                  </div>
                </div>
              </div>
              <br>
              <div class="container">
                <div class="row">
                  <div class="col-md-12">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                        <th scope="col">Department Name</th>
                        <th scope="col">Employee Name</th>
                        <th scope="col">Access Utility</th>
                        <th scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody id="employeesList">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>
            <!-- end employees-tab -->

            <!-- start reports-tab -->
            <div class="tab-pane" id="reports" role="tabpanel" aria-labelledby="reports-tab" tabindex="0">
              <div class="container">
                <div class="row">
                  <div class="col-md-12">
                    <br>
                    <div class="d-grid gap-2 col-6 mx-auto">
                      <button class="btn btn-primary" type="button" id="exportEmployeeReports">Export Report Inquiry</button>
                    </div>
                  </div>
                </div>
              </div>
                  
            </div>
            <!-- end reports-tab -->

            <!-- start settings-tab -->
            <div class="tab-pane" id="settings" role="tabpanel" aria-labelledby="settings-tab" tabindex="0">

            </div>
            <!-- end settings-tab -->

          </div>
        </div>
      </div>

    </div>
    <!-- End Main content card -->

    <!-- Create Department Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Create Department</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Form -->
            <div class="mb-3">
                <label for="name" class="form-label">Department Name <span class="text-danger">*</span> </label>
                <input type="text" class="form-control" id="name" placeholder="IT Department">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" rows="3"></textarea>
            </div>
            <!-- End Form -->
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" id="createDepartmentBtn" class="btn btn-primary">Create</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End Create Department Modal -->

    <!-- Update Department Modal -->
    <div class="modal fade" id="staticBackdropUpdate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Update Department</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Form -->
            <div class="mb-3">
                <label for="name" class="form-label">Department Name <span class="text-danger">*</span> </label>
                <input type="text" class="form-control" id="updateName">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="updateDescription" rows="3"></textarea>
            </div>
            <input type="hidden" id="departmentId">
            <!-- End Form -->
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" id="updateDepartmentBtn" class="btn btn-primary">Update</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End Update Department Modal -->

    <!-- Create Employee Modal -->
    <div class="modal fade" id="staticBackdropCreateEmployee" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Create Employee</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Form -->
            <div class="mb-3">
              <label for="createEmployeeName" class="form-label">Employee Name <span class="text-danger">*</span> </label>
              <input type="text" class="form-control" id="createEmployeeName" placeholder="Rhoel Cartojano Jr">
            </div>
            <div class="mb-3">
              <label for="createEmployeeEmail" class="form-label">Employee Email <span class="text-danger">*</span> </label>
              <input type="text" class="form-control" id="createEmployeeEmail" placeholder="example@company.com">
            </div>
            <div class="mb-3">
              <label for="selectDepartment" class="form-label">Department <span class="text-danger">*</span> </label>
              <select class="form-control" id="selectDepartment">
                
              </select>
            </div>
            <div class="mb-3">
            <label for="name" class="form-label">Access Utilities <span class="text-danger">*</span> </label>
              <ul class="list-group">
                <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" id="chxAdd">
                  <label class="form-check-label" for="chxAdd">Add</label>
                </li>
                <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" id="chxEdit">
                  <label class="form-check-label" for="chxEdit">Edit</label>
                </li>
                <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" id="chxDelete">
                  <label class="form-check-label" for="chxDelete">Delete</label>
                </li>
                <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" id="chxView">
                  <label class="form-check-label" for="chxView">View</label>
                </li>
                <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" id="chxSearch">
                  <label class="form-check-label" for="chxSearch">Search</label>
                </li>
                <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" id="chxPrint">
                  <label class="form-check-label" for="chxPrint">Print</label>
                </li>
                <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" id="chxExport">
                  <label class="form-check-label" for="chxExport">Export</label>
                </li>
              </ul>
            </div>

            <!-- End Form -->
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" id="createEmployeeBtn" class="btn btn-primary">Create</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End Create Employee Modal -->

    <!-- Update Employee Modal -->
    <div class="modal fade" id="staticBackdropUpdateEmployee" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Update Employee</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Form -->
            <div class="mb-3">
                <label for="name" class="form-label">Employee Name <span class="text-danger">*</span> </label>
                <input type="text" class="form-control" id="updateEmployeeName">
            </div>
            <div class="mb-3">
              <label for="updateEmployeeDepartment" class="form-label">Department <span class="text-danger">*</span> </label>
              <select class="form-control" id="updateEmployeeDepartment">
                
              </select>
            </div>
            <div class="mb-3">
            <label for="name" class="form-label">Access Utilities <span class="text-danger">*</span> </label>
              <ul class="list-group">
                <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" id="chxEditAdd">
                  <label class="form-check-label" for="chxEditAdd">Add</label>
                </li>
                <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" id="chxEditEdit">
                  <label class="form-check-label" for="chxEditEdit">Edit</label>
                </li>
                <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" id="chxEditDelete">
                  <label class="form-check-label" for="chxEditDelete">Delete</label>
                </li>
                <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" id="chxEditView">
                  <label class="form-check-label" for="chxEditView">View</label>
                </li>
                <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" id="chxEditSearch">
                  <label class="form-check-label" for="chxEditSearch">Search</label>
                </li>
                <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" id="chxEditPrint">
                  <label class="form-check-label" for="chxEditPrint">Print</label>
                </li>
                <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" id="chxEditExport">
                  <label class="form-check-label" for="chxEditExport">Export</label>
                </li>
              </ul>
            </div>
            <input type="hidden" id="employeeId">
            <!-- End Form -->
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" id="updateEmployeeBtn" class="btn btn-primary">Update</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End Update Employee Modal -->

    <!-- Start Employee Account Credentials Modal -->
    <div class="modal" id="staticBackdropShowEmployeeCredentials" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Employee Account Credentials</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><i>Please send over to employee the login credentials, an email server will come soon for automation.</i><p>
            <br>
            <div id="printArea">
              <label class="form-label">Employee Email/Username: <span id="employeeUsername"></span></label>
              <br>
              <label class="form-label">Employee Password: <span id="employeePassword"></span> </label>
            </div>
            <br><br>
            <small>Note: Kindly advise that the password will expire within 36hrs and need to change it immediately.</small>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" id="printEmployeeCredentialsBtn" class="btn btn-primary">Print</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End Employee Account Credentials Modal -->
    
  </div>
</div>
@endsection

@section('departmentjs')
    <script src="department.js"></script>
@endsection

@section('employeesjs')
    <script src="employees.js"></script>
@endsection

@section('reportsjs')
    <script src="reports.js"></script>
@endsection
