<!-- OFFCANVAS Oraganization Builder SIDEBAR -->
<div class="offcanvas offcanvas-end show bg-dark text-white overflow-auto text-nowrap hierachy-area hierachy"
    tabindex="-1" id="hierachy-builder" aria-labelledby="hierachyBar">

    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="hierachyBar">Organization Builder</h5>
        <button type="button" class="btn-close text-white btn-close-display" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>

    <div class="offcanvas-body mt-0 pt-0">
        <div class="col-12 mt-0" id="hierachy-form">

        </div>
 
        <div class="navbar-dark px-2" id="hierachy-structure">
            <ul class="navbar-nav" id="hierachy-list">

            </ul>
        </div>
    </div>
</div>

<div class="accordion w-100 mx-0" id="accordionHierachy">
    <!-- Organization Det -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOrgDet">
            <button class="accordion-button p-2 btn-outline-secondary collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseOrgDet" aria-expanded="false" aria-controls="collapseOrgDet" id="buttonOrgDet">
                Organization Details
            </button>
        </h2>
        <div id="collapseOrgDet" class="accordion-collapse collapse border" aria-labelledby="headingOrgDet"
            data-bs-parent="#accordionHierachy">
            <div class="accordion-body border p-1" id="collapseOrgDetBody">
                <div class="container">
                    <div class="row m-0 p-0 mt-3">
                        <div class="col-6 text-center">
                            <!-- offcanvas button -->
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#hierachy-builder" aria-controls="hierachy-builder"
                                id="hierachy-button">
                                Sidebar
                            </button>
                        </div>
                        <div class="col-6 text-center">
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOrganogram" aria-expanded="true"
                                aria-controls="collapseOrganogram">
                                Organogram
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h5 class="mt-3">Please select an organization from the sidebar or organogram.</h5>
                        <p class="text-start">The full organizational details will appear in this area when the
                            organization's gear icon is
                            clicked.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Organogram -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOrganogram">
            <button class="accordion-button p-2 btn-outline-secondary" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseOrganogram" aria-expanded="true" aria-controls="collapseOrganogram"
                id="buttonOrganogram">
                Organogram
            </button>
        </h2>
        <div id="collapseOrganogram" class="accordion-collapse collapse show" aria-labelledby="headingOrganogram"
            data-bs-parent="#accordionHierachy">
            <div class="accordion-body m-0 p-0">
                <div class="container m-0 p-0">
                    <div class="row m-0 p-0 mt-3">
                        <div class="col-6 text-center">
                            <!-- offcanvas button -->
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#hierachy-builder" aria-controls="hierachy-builder"
                                id="hierachy-button">
                                Sidebar
                            </button>
                        </div>
                        <div class="col-6 text-center">
                            <button class="btn btn-sm btn-outline-secondary" type="button" onclick="draw();"
                                id="hierachy-draw-button">Refresh</button>
                        </div>
                    </div>
                    <div class="row m-0 p-0 mt-3">
                        <div class="col-12  m-0 p-0">
                            <canvas class="inline-block m-0 p-0 border" id="hierachy" class="hierachy"></canvas>
                            <div style="display:none;">
                                <img width="30" height="30"
                                    src="https://<?php echo $config['url']['server']; ?>/svg/arrow-down-short.svg" id="svg-down">
                                <img width="30" height="30"
                                    src="https://<?php echo $config['url']['server']; ?>/svg/arrow-up-short.svg" id="svg-up">
                                <img width="30" height="30"
                                    src="https://<?php echo $config['url']['server']; ?>/svg/arrow-left-short.svg" id="svg-left">
                                <img width="30" height="30"
                                    src="https://<?php echo $config['url']['server']; ?>/svg/arrow-right-short.svg" id="svg-right">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>