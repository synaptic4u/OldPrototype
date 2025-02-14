<form class="needs-validation" method="POST" action="" id="User" novalidate>
    <div class="container-flex mt-3" id="User">
        <div class="row justify-content-center">
            <div class="col-md-8 col-sm-12">
                <div class="container">

                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h5 class="h5">Please add your details</h5>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1  position-relative">
                                <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">First Name</span></legend>
                                <input minLength="2"
                                    class="w-100 p-2 form-control required <?php echo (!is_null($firstname['pass'])) ? $firstname['message'] : ''; ?>"
                                    type="text" placeholder="First Name" aria-describedby="firstname" name="firstname"
                                    value="<?php echo (!is_null($firstname['pass'])) ? $firstname['value'] : ''; ?>"
                                    required />
                                <div class="valid-feedback">
                                    Looks good.
                                </div>
                                <div class="invalid-feedback">
                                    Your first name is required and a minimum of 2 characters.
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                                <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Surname</span></legend>
                                <input minLength="2"
                                    class="w-100 p-2 form-control required <?php echo (!is_null($surname['pass'])) ? $surname['message'] : ''; ?>"
                                    type="text" placeholder="Surname" aria-describedby="surname" name="surname"
                                    value="<?php echo (!is_null($surname['pass'])) ? $surname['value'] : ''; ?>"
                                    required />
                                <div class="valid-feedback">
                                    Looks good.
                                </div>
                                <div class="invalid-feedback">
                                    Your surname is required and a minimum of 2 characters.
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                                <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Contact Number</span>
                                </legend>
                                <input minLength="10"
                                    class="w-100 p-2 form-control required <?php echo (!is_null($contactnu['pass'])) ? $contactnu['message'] : ''; ?>"
                                    type="text" placeholder="Contact Number" aria-describedby="contactnu"
                                    name="contactnu"
                                    value="<?php echo (!is_null($contactnu['pass'])) ? $contactnu['value'] : ''; ?>"
                                    required />
                                <div class="valid-feedback">
                                    Looks good.
                                </div>
                                <div class="invalid-feedback">
                                    A minimum of 10 character is required.
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                                <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Email Address</span>
                                </legend>
                                <input minLength="10"
                                    class="w-100 p-2 form-control required <?php echo (!is_null($email['pass'])) ? $email['message'] : ''; ?>"
                                    type="email" placeholder="Email Address" aria-describedby="email" name="email"
                                    value="<?php echo (!is_null($email['pass'])) ? $email['value'] : ''; ?>" required />
                                <div class="valid-feedback">
                                    Looks good.
                                </div>
                                <div class="invalid-feedback">
                                    A minimum of 10 characters and a valid domain name is required.
                                    <?php echo (!is_null($email_exists)) ? 'This email is already in use!' : ''; ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                                <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Password</span></legend>
                                <input minLength="15"
                                    class="w-100 p-2 form-control required <?php echo (!is_null($passkey['pass'])) ? $passkey['message'] : ''; ?>"
                                    type="password" placeholder="Password" aria-describedby="passkey" name="passkey"
                                    value="<?php echo (!is_null($passkey['pass'])) ? $passkey['value'] : ''; ?>"
                                    required />
                                <div class="valid-feedback">
                                    Looks good.
                                </div>
                                <div class="invalid-feedback">
                                    A minimum of 15 character is requireds.<br>
                                    No "<" or ">" characters allowed. </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-sm-12 height:250px">
                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                                <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">POPI Compliance</span>
                                </legend>
                                <div style="height: 200px;" class="custom-control custom-switch overflow-auto"
                                    id="popi-scroller">
                                    <p>
                                        POPI ACT AGREEMENT AND CONSENT DECLARATIONYOU HEREBY DECLARE AND CONFIRM
                                        THAT
                                        YOU,
                                        AS THE PERSON / ENTITY / BODY / INDIVIDUAL / COMPANY WHO IS PROVIDING
                                        INFORMATION
                                        AND
                                        HEREIN AFTER COLLECTIVELY REFERRED TO AS THE“CLIENT”, DO HEREBY IRREVOCABLY
                                        AGREE
                                        AND
                                        UNDERSTAND THAT ANY / ALL INFORMATION SUPPLIED OR GIVEN TO THE SERVICE
                                        PROVIDER,IS
                                        DONE SO IN TERMS OF THE BELOW TERMS AND CONDITIONS AND INTERMS OF THIS
                                        AGREEMENT
                                        AND
                                        CONSENT DECLARATION. Emile De Wilde as Synaptic4U (“THE SERVICE
                                        PROVIDER/COMPANY”)
                                    </p>

                                    <ul class="list-unstyled">
                                        <li>1. INTERPRETATION</li>
                                        <li>
                                            1.1 In this Agreement, unless inconsistent with or otherwise indicated
                                            by
                                            the
                                            context –
                                        </li>
                                        <li>
                                            1.1.1 This Agreement” means the Agreement contained in this document;
                                        </li>
                                        <li>
                                            1.1.2 The Company/Service provider” means Emile De Wilde as Synaptic4U
                                            and
                                            includes
                                            its affiliated, holding and subsidiary companies;
                                        </li>
                                        <li>
                                            1.1.3 “Confidential information” includes, but is not limited to:
                                        </li>
                                        <li>
                                            1.1.3.1 any information in respect of know-how, formulae, processes,
                                            systems,
                                            business methods, marketing methods, promotionalplans, financial models,
                                            inventions,
                                            long-term plans and anyother information of the client and the company
                                            in
                                            whatever
                                            formit may be;
                                        </li>
                                        <li>
                                            1.1.3.2 all internal control systems of the client and the company;
                                        </li>
                                        <li>
                                            1.1.3.3 details of the financial structure and any other financial,
                                            operational
                                            information of the client and the company; and
                                        </li>
                                        <li>
                                            1.1.3.4 any arrangements between the client and the company and
                                            otherswith
                                            whom
                                            they
                                            have business arrangements of whatsoevernature, all of which the client
                                            and
                                            the
                                            company regards as secretand confidential.
                                        </li>
                                        <li>
                                            1.1.4 “personal information” means personal information as defined in
                                            the
                                            Protection
                                            of Personal Information Act adopted by the Republic of SouthAfrica on 26
                                            November
                                            2013 and includes but is not limited to:
                                        </li>
                                        <li>
                                            1.1.4.1 information relating to the race, gender, sex,
                                            pregnancy, marital status, national, ethnic or social origin, colour,
                                            sexual orientation, age, physical or mental health,
                                            well-being,disability,
                                            religion,
                                            conscience, belief, culture, language and birth of the person;
                                        </li>
                                        <li>
                                            1.1.4.2 information relating to the education or the medical, financial,
                                            criminal or
                                            employment history of the person;
                                        </li>
                                        <li>
                                            1.1.4.3 any identifying number, symbol, e-mail address,
                                            physicaladdress, telephone number, location information, online
                                            identifier
                                            or
                                            other
                                            particular assignment to the person;
                                        </li>
                                        <li>
                                            1.1.4.4 the biometric information of the person;
                                        </li>
                                        <li>
                                            1.1.4.5 the personal opinions, views or preferences of the person;
                                        </li>
                                        <li>
                                            1.1.4.6 correspondence sent by the person that is
                                            implicitly or explicitly of a private or confidential nature or further
                                            correspondence
                                            that would reveal the contents of the original correspondence;
                                        </li>
                                        <li>
                                            1.1.4.7 the views or opinions of another individual about the person;
                                            and
                                        </li>
                                        <li>
                                            1.1.4.8 the name of the person if it appears with other personal
                                            information
                                            relating
                                            to the person or if the disclosure of thename itself would reveal
                                            information
                                            about
                                            the person.
                                        </li>
                                        <li>
                                            1.1.5 “the effective date” means the date of signature of this
                                            Agreement’;
                                        </li>
                                        <li>
                                            1.1.6 “the parties” means the parties as described herein above;
                                        </li>
                                        <li>
                                            1.1.7 “divulge” or “make use of” means to
                                            reveal, make known, disclose, divulge, make public, release, publicise,
                                            broadcast,
                                            communicate or correspond or any such other manners of divulging of any
                                            information.
                                        </li>
                                        <li>
                                            1.1.8 "processing" means any operation or activity or any set of
                                            operations, whether or not by automatic means, concerning personal or
                                            any
                                            information,
                                            including but not limited to :
                                        </li>
                                        <li>
                                            (a) the collection, receipt, recording, organisation, collation,
                                            storage,
                                            updating
                                            or modification, retrieval, alteration, consultation or use;(b)
                                            dissemination by
                                            means of transmission, distribution or making available in any other
                                            form;
                                            or(c)merging, linking, as well as restriction, degradation, erasure or
                                            destruction
                                            of information.
                                        </li>
                                        <li>
                                            1.1.9 POPI” means the Protection of Personal Information Act adopted
                                            by the Republic of South Africa on 26 November 2013 and as amended from
                                            time
                                            to
                                            time. WHEREAS IT IS AGREED THAT All parties agree that they will comply
                                            with
                                            POPI
                                            regulations and process all the information and / or personal data in
                                            respect of
                                            the
                                            services being rendered in accordance with the said regulation and only
                                            for
                                            the
                                            purpose of providing the Services set out in the agreement to provide
                                            services.The
                                            company (also called the service provider), all the parties to this
                                            agreement,
                                            the
                                            service provider’s employees and the client’s employees and any
                                            subsequent
                                            party / parties to this agreement acknowledge and confirm that One or
                                            more
                                            of
                                            the
                                            parties to this agreement, will possess and will continue to possess
                                            information
                                            that
                                            may be classified or maybe deemed as private, confidential or aspersonal
                                            information.
                                            Such information may be deemed as the private, confidential or as
                                            personal
                                            information in so far as it relates to any party to this agreement. Such
                                            information
                                            may also be deemed as or considered as private, confidential or
                                            aspersonal
                                            information of any third person who may be directly or indirectly
                                            associated
                                            with
                                            this agreement. Further it is acknowledged and agreed by all parties to
                                            this
                                            agreement, that such private, confidential or as personal information
                                            may
                                            have
                                            value
                                            and such information may ormay not be in the public domain. For purposes
                                            of
                                            rendering services on behalf of the client, the service provider and any
                                            party associated with this agreement and/or any subsequent or prior
                                            agreement
                                            that
                                            may have been / will be entered into, irrevocably agree that
                                            “confidential
                                            information”
                                            shall also include inter alia and shall mean inter alia: (a)all
                                            information
                                            of
                                            any
                                            party which may or may not be marked "confidential", "restricted",
                                            "proprietary"
                                            or
                                            with a similar designation; (b)where applicable, any and all data and
                                            business
                                            information; (c) where applicable the parties may have access to data
                                            and
                                            personal
                                            and business information regarding clients, employees, third parties and
                                            the
                                            like
                                            including personal information as defined in POPI regulation; and (d)
                                            trade
                                            secrets,
                                            confidential knowledge, know-how, technical information, data or other
                                            proprietary
                                            information relating to the client / service provider or any third party
                                            associated
                                            with this agreement and (including, without limitation, all products
                                            information,
                                            technical know how, software programs, computer processing systems and
                                            techniques
                                            employed or used by either party to this agreement and / or their
                                            affiliates.
                                            By signature hereunder, all parties irrevocably agree to abide by the
                                            terms
                                            and
                                            conditions as set out in this agreement as well as you irrevocably agree
                                            and
                                            acknowledge that all information provided, whether personal or
                                            otherwise,
                                            may be
                                            used and processed by the service provider and such use may include
                                            placing
                                            such
                                            information in the public domain. Further it is specifically agreed that
                                            the
                                            service
                                            provider will use its best endeavors and take all reasonable precautions
                                            to
                                            ensure
                                            that any information provided, is only used for the purposes its has
                                            been
                                            provided.
                                            It is agreed that such information may be placed in the public domain
                                            and by
                                            signature here under, all parties acknowledge that they have read all of
                                            the
                                            terms
                                            in this policy and thatthey understand and agree to be bound by the
                                            terms
                                            and
                                            conditions as set out in this agreement. It is confirmed that by
                                            submitting
                                            information to the service provider, irrespective as to how such
                                            information
                                            is
                                            submitted, you consent to the collection, collation, processing, and
                                            storing
                                            of
                                            such
                                            information and the use and disclosure of such information in accordance
                                            with
                                            this
                                            policy.
                                        </li>
                                    </ul>

                                    <p>
                                        SHOULD YOU NOT AGREE TO THE TERMS AND CONDITIONS ASSET OUT IN THIS
                                        AGREEMENT
                                        AND CONSENT DECLARATION YOU MUST NOTIFY THE SERVICE PROVIDER IMMEDIATELY
                                        FAILING
                                        WHICH IT WILL BE DEEMED THAT YOU ACCEPT AND AGREE TO THE TERMS AND
                                        CONDITIONS
                                        SET
                                        OUT
                                        ABOVE
                                    </p>

                                    <input type="checkbox"
                                        class="custom-control-input required <?php echo (!is_null($popi_compliance['pass'])) ? $popi_compliance['message'] : ''; ?>"
                                        name="popi_compliance" id="popi_compliance"
                                        <?php echo ((int) $popi_compliance['value'] === (int) 1) ? 'checked' : ''; ?>
                                        required />
                                    <label class="custom-control-label mb-1" for="popi_compliance">
                                        I agree to have my information stored in this application.
                                    </label>
                                    <div class="valid-feedback">
                                        Looks good.
                                    </div>
                                    <div class="invalid-feedback">
                                        You have to agree to have your information stored in this application.
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row mt-2 mb-5">
                        <div class="col-sm-12 text-center">
                            <button class="btn btn-sm btn-outline-primary" type="button" onclick="grab('init','<?php echo $store; ?>','<?php echo $user; ?>',
                                        this.form.id);">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>