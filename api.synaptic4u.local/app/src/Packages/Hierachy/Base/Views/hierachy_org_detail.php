<!-- Buttons & Heading details -->
<div class="container-sm">
    <div class="row m-0 p-0 mt-3">
        <div class="col-6 text-center">
            <!-- offcanvas button -->
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#hierachy-builder" aria-controls="hierachy-builder" id="hierachy-button">
                Sidebar
            </button>
        </div>
        <div class="col-6 text-center">
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseOrganogram" aria-expanded="true" aria-controls="collapseOrganogram">
                Organogram
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-0">
                <legend class="w-auto float-none">
                    <span class="ps-2 pe-2 h6">
                        Organization
                    </span>
                </legend>
                <h6 class="h5 ms-2 my-1 text-nowrap overflow-auto header-hierachy-name"><?php echo $hierachyname; ?>
                </h6>
            </fieldset>
        </div>
        <div class="col-sm-6">
            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-0">
                <legend class="w-auto float-none">
                    <span class="ps-2 pe-2 h6">
                        Maintainer
                    </span>
                </legend>
                <h6 class="h5 ms-2 my-1 text-nowrap overflow-auto header-hierachy-creator">
                    <?php echo $hierachycreator; ?>
                </h6>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <hr class="hr w-100">
        </div>
    </div>
</div>
<!-- Organization Details, Particulars, Applications, Roles & Types -->
<div class="container-sm">
    <div class="accordion mx-0 px-0 border bg-dark" id="accordionOrganizationDetails">
        <!-- Organization Details -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="OrganizationDetails">
                <button class="accordion-button p-2 btn-outline-secondary collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseOrganizationDetailsForm" aria-expanded="false"
                    aria-controls="collapseOrganizationDetailsForm">
                    Organization Details
                </button>
            </h2>
            <div id="collapseOrganizationDetailsForm" class="accordion-collapse collapse"
                aria-labelledby="OrganizationDetails" data-bs-parent="#accordionOrganizationDetails">
                <div class="accordion-body p-2" id="collapseOrganizationDetailsFormBody">
                    Organization Details information.
                </div>
            </div>
        </div>
        <!-- Organization Particulars -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="OrganizationParticulars">
                <button class="accordion-button p-2 btn-outline-secondary collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseOrganizationParticularsForm"
                    aria-expanded="false" aria-controls="collapseOrganizationParticularsForm">
                    Organization Particulars
                </button>
            </h2>
            <div id="collapseOrganizationParticularsForm" class="accordion-collapse collapse"
                aria-labelledby="OrganizationParticulars" data-bs-parent="#accordionOrganizationDetails">
                <div class="accordion-body p-2" id="collapseOrganizationParticularsFormBody">
                    Organization particulars information.
                </div>
            </div>
        </div>
        <!-- Organization Applications -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="OrganizationApplications">
                <button class="accordion-button p-2 btn-outline-secondary collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseOrganizationApplicationsDetails"
                    aria-expanded="false" aria-controls="collapseOrganizationApplicationsDetails">
                    Organization Applications
                </button>
            </h2>
            <div id="collapseOrganizationApplicationsDetails" class="accordion-collapse collapse"
                aria-labelledby="OrganizationApplications" data-bs-parent="#accordionOrganizationDetails">
                <div class="accordion-body p-1" id="collapseOrganizationApplicationsDetailsBody">
                    Organization Applications information.
                </div>
            </div>
        </div>
        <!-- Organization Types -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="OrganizationTypes">
                <button class="accordion-button p-2 btn-outline-secondary collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseOrganizationTypes" aria-expanded="false"
                    aria-controls="collapseOrganizationTypes">
                    Organization Types
                </button>
            </h2>
            <div id="collapseOrganizationTypes" class="accordion-collapse collapse" aria-labelledby="OrganizationTypes"
                data-bs-parent="#accordionOrganizationDetails">
                <div class="accordion-body p-1" id="collapseOrganizationTypesBody">
                    List of all the different types the organization uses.
                </div>
            </div>
        </div>
        <!-- Organization Roles -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="OrganizationRoles">
                <button class="accordion-button p-2 btn-outline-secondary collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseOrganizationRoles" aria-expanded="false"
                    aria-controls="collapseOrganizationRoles">
                    Organization Roles
                </button>
            </h2>
            <div id="collapseOrganizationRoles" class="accordion-collapse collapse" aria-labelledby="OrganizationRoles"
                data-bs-parent="#accordionOrganizationDetails">
                <div class="accordion-body p-1" id="collapseOrganizationRolesBody">
                    List of all the different roles the organization uses.
                </div>
            </div>
        </div>
        <!-- Hierachy Users -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="HeadingHierachyUsers">
                <button class="accordion-button p-2 btn-outline-secondary collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseHierachyUsers" aria-expanded="false"
                    aria-controls="collapseHierachyUsers">
                    Organization Users
                </button>
            </h2>
            <div id="collapseHierachyUsers" class="accordion-collapse collapse border" aria-labelledby="HeadingHierachyUsers"
                data-bs-parent="#accordionOrganizationDetails">
                <div class="accordion-body border p-1" id="collapseHierachyUsersBody">
                    List of all the users belongong to this organization.
                </div>
            </div>
        </div>
        <!-- Application Roles -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="HeadingApplicationsRoles">
                <button class="accordion-button p-2 btn-outline-secondary collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#ApplicationsRoles" aria-expanded="false"
                    aria-controls="ApplicationsRoles">
                    Organization Application Roles
                </button>
            </h2>
            <div id="ApplicationsRoles" class="accordion-collapse collapse" aria-labelledby="HeadingApplicationsRoles"
                data-bs-parent="#accordionOrganizationDetails">
                <div class="accordion-body border p-1" id="collapseHierachyApplicationsRolesBody">
                    List of all applications for this organization.<br>Each aspect can be assigned with a user role, defining that role's privilege.
                </div>
            </div>
        </div>
    </div>
</div>