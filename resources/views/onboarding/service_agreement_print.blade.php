@php

    $JAId = base64_decode($_REQUEST['jaid']);
    $sql = DB::table('jobapply')
        ->leftJoin('appointing', 'appointing.JAId', '=', 'jobapply.JAId')
        ->leftJoin('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')
        ->leftJoin('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
        ->leftJoin('candjoining', 'jobapply.JAId', '=', 'candjoining.JAId')
        ->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')
        ->leftJoin('jf_family_det', 'jobcandidates.JCId', '=', 'jf_family_det.JCId')
        ->select('appointing.*', 'offerletterbasic.*', 'candjoining.JoinOnDt', 'jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.FatherTitle', 'jobcandidates.FatherName', 'jobcandidates.Gender', 'jobcandidates.Aadhaar', 'jobcandidates.Email', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_dist', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin')
        ->where('jobapply.JAId', $JAId)
        ->first();
    $months_word = ['One' => '1 (One)', 'Two' => '2 (Two)', 'Three' => '3 (Three)', 'Four' => '4 (Four)', 'Five' => '5 (Five)', 'Six' => '6 (Six)', 'Seven' => '7 (Seven)', 'Eight' => '8 (Eight)', 'Nine' => '9 (Nine)', 'Ten' => '10 (Ten)', 'Eleven' => '11 (Eleven)', 'Twelve' => '12 (Twelve)'];
@endphp
<style>
    ol,
    li {
        text-align: justify;
    }

    .abc {
        /* margin-left: 42.55pt;*/
        text-align: justify;
        text-justify: inter-ideograph;
        text-indent: -28.35pt
    }

    .bde {
        margin-left: 62.55pt;
        text-align: justify;
        text-justify: inter-ideograph;
        text-indent: -38.35pt
    }

</style>

<p style="text-align: center;font-size:16px;"><b>Service Agreement</b></p>
<p style="text-align: justify"> This service agreement ("<strong>Agreement</strong>") is made at
    Raipur, Chhattisgarh on
    {{ date('d/m/Y', strtotime($sql->JoinOnDt)) }}
</p>
<p><b>BY AND BETWEEN:</b></p>
<ol>
    <li>
        <p style="text-align: justify"><b> {{ strtoupper(getcompany_name($sql->Company)) }}</b>, a
            company
            registered under the laws of India with corporate identification number:
            {{ $sql->Company == 1 ? 'U00512CT2004PTC017187' : 'U01400CT2012PTC000139' }},
            having its registered office at Corporate Centre, Canal Road Crossing, Ring Road No.1,
            Raipur,
            Chhattisgarh- 492006 (hereinafter referred to as <strong>“Company”</strong>, which
            expression
            shall include its
            successors and permitted assigns); and </p>
    </li>
    <li>
        <p style="text-align: justify">
            <b>{{ $sql->Title }} {{ $sql->FName }} {{ $sql->MName }}
                {{ $sql->LName }}</b>, a citizen of India with Aadhaar No.
            <b>{{ $sql->Aadhaar }}</b> and residing at {{ $sql->perm_address }},
            {{ $sql->perm_city }},
            Dist-{{ getDistrictName($sql->perm_dist) }} ({{ getStateCode($sql->perm_state) }})
            -
            {{ $sql->perm_pin }} (hereinafter referred to as the <b>“Executive”</b>).
        </p>
    </li>
</ol>
<p style="text-align: justify;">The Company and the Executive are colectively referred to as the
    <b>"Parties"</b> and
    individually as a <b>"Party"</b>.
</p>

<p style="font-weight: bold">WHEREAS:</p>

<ol type="A">
    <li>The Company is engaged in the business of research, procurement, processing,
        distribution, sales and marketing of vegetable crop, field crop and fruit crop seeds
        (“<b>Business</b>”).
    </li>
    <li>The Parties are desirous to record the terms and conditions of the Executive's employment
        with the Company in writing for good order and the terms and conditions between the Parties
        will be governed by the terms of this Agreement.
    </li>
    <li>Pursuant to the terms of this Agreement, the Executive will undertake various duties and
        responsibilities, in the best interest of the Company.
    </li>
</ol>

<p style="text-align: justify"><b>NOW, THEREFORE,</b> for and in consideration of the above
    premises, the
    mutual covenants and agreements hereinafter set forth and other good and valuable consideration,
    the receipt, adequacy, and sufficiency of which are hereby acknowledged, the parties hereto
    covenant and agree as follows:</p>

<b>
    <p>1. DEFINITIONS AND INTERPRETATION</p>
</b>
<p>1.1 <u>Defination</u></p>
<p style="text-align: justify">In the Agreement, (i) capitalised terms defined by inclusion in
    quotations and/or parenthesis have the meanings so ascribed; and (ii) the following terms shall
    have the meanings assigned to them herein below:</p>
<ul type="none">
    <li>1.1.1 <b>"Affiliate"</b> means:</li>
    <li>
        <ol type="i">
            <li>with respect to any Person, any other Person that, directly or indirectly, Controls,
                or is Controlled by, or is under Common Control with, such Person; and
            </li>
            <li>with respect to any Person being a natural person:</li>
        </ol>
    <li>
        <ul type="none">
            <li>
                <ol type="A">
                    <li>any Person Controlled directly or indirectly, by that Person or his/ her
                        Relatives;
                    </li>
                    <li>any trust, of which such Person or his/her Relative, is a direct or indirect
                        beneficiary; and
                    </li>
                    <li>his/ her Relatives.</li>
                </ol>
            </li>
        </ul>
    </li>
    </li>
</ul>
<p class="abc"><span>1.1.2</span> <span>“<b>Agreed Term</b>” shall have the meaning
        ascribed to it in Clause 3.1;</span>
</p>

<p class="abc"><span>1.1.3</span> <span>“<b>Agreement</b>” shall mean this employment
        agreement, including all exhibits, annexures, schedules and appendices attached hereto and
        all instruments supplemental to or in amendment or furtherance or confirmation of this
        agreement, entered into in writing, in accordance with its terms; </span></p>

<p class="abc"><span>1.1.4</span> <span>“<b>Board</b>” means the board of directors of
        the
        Company.</span></p>
<p class="abc"><span>1.1.5</span> <span>“<b>Business IP</b>” means all Proprietary Rights
        owned, used, or required to be used by the Company in or in connection with the Business.
    </span></p>
<p class="abc"><span>1.1.6</span> <span>“<b>Business Day/s</b>” mean any day other than
        Sunday or Declared holiday. </span></p>
<p class="abc"><span>1.1.7</span> <span>“<b>Cause</b>” for termination of Executive’s
        employment with the Company shall mean the occurrence of one or more of the following
        events: </span></p>
<ul type="none">
    <li>
        <ol type="i">
            <li>Dishonesty or fraud by the Executive in the performance of his / her duties for the
                Company or its Affiliates;
            </li>
            <li>Violation of the Company’s policies and procedures in effect from time to time
                and which, to the extent a cure is reasonably possible, remains uncured 10 (Ten)
                days after written notice of such violation is given to Executive by the Company;
            </li>
            <li>Failure by the Executive to satisfactorily perform the Executive’s duties
                after receipt of written notice that specifically identifies the areas in which the
                Executive’s performance is deficient and which remains uncured 10 (Ten) days after
                such written notice is given to the Executive by the Company;
            </li>
            <li>Actions (or failures to act) by the Executive that impairs business, interest,
                goodwill, or reputation of the Company or its Affiliates;
            </li>
            <li>Executive’s conviction of a felony or any crime involving an act of dishonesty,
                moral turpitude, deceit or fraud, or the commission of acts that would reasonably be
                expected to result in such conviction;
            </li>
            <li>Executive becoming the subject of a bankruptcy/insolvency petition or taking
                advantage of any law for the time being in force affording relief for insolvent
                debtors or has a receiver or administrator appointed over all or a substantial part
                of his / her assets;
            </li>
            <li>Executive committing a breach of any terms of this Agreement or breach of
                representations and warranties contained herein;
            </li>
            <li>Executive committing breach of any statutory duty or for any act or omission
                adversely affecting the goodwill, reputation, credit, operations, or business of the
                Company or its Affiliates;
            </li>
            <li>Habitual unauthorised absence or unauthorised absence by the Executive for a period
                exceeding [10 (ten)] days;
            </li>
            <li>wilful damage by the Executive to the property of the Company or its Affiliates;
            </li>
            <li>any form of harassment, including sexual harassment, committed by the Executed while
                employed with the Company;
            </li>
            <li>unauthorised disclosure of any Confidential Information of the Company or its
                Affiliates;
            </li>
            <li>making derogatory, malicious, or false statements in public or on social media
                against the Company or its Affiliates; or
            </li>
            <li>Executive’s breach of Clause 11.</li>
        </ol>
    </li>
</ul>
<p class="abc"><span>1.1.8</span> <span>“<b>Competing Business</b>” means seeds
        processing operations, seeds sales and marketing activities, seeds research related
        activities (breeding, biotechnology, and breeding support), seeds production activities,
        seeds production research activities, parental seeds production activities, software
        development, product development activities, etc. </span></p>
<p class="abc"><span>1.1.9</span> <span>“<b>Confidential Information</b>” shall mean any
        confidential and/or proprietary information concerning the Company and/or its Affiliates,
        disclosed, either directly or indirectly, including in writing or by inspection of tangible
        objects including without limitation: (a) inventions, innovations or intellectual property
        rights, protocols and any idea or know-how; (b) confidential and proprietary trade secrets
        of the Company and/or its Affiliates, all other information belonging or relating to their
        business that is not generally known; (c) proprietary information relating to the
        development, utility, operation, functionality, performance, cost, know-how; and (d) details
        of present and proposed businesses, formulas, ideas, strategies, techniques, policy, data
        related to employees, past present or proposed customers, vendors, agents, suppliers,
        affiliates information regarding research and development, unpublished financial statements,
        budgets and other financial details, computer programming techniques, methodologies and
        related technical information, business or marketing plans, forecasts, controls, operating
        procedures, organization responsibilities, marketing matters and any policies or procedures,
        software programs and files, operating manuals, user manuals documentation, object code,
        source code and any and all information pertaining to the application of any software;
    </span></p>
<p class="abc"><span>1.1.10</span> <span>“<b>Control</b>” shall mean, with respect to any
        Person: (i) the ownership of more than 50% (fifty percent) of the equity shares or other
        voting securities of such Person; or (ii) the possession of the power to direct the
        management and policies of such Person; or (iii) the power to appoint a majority of the
        directors, managers, partners or other individuals exercising similar authority with respect
        to such Person by virtue of ownership of voting securities or management or contract or in
        any other manner, whether directly or indirectly, including through one or more other
        Persons; and the term “Common Control” and “Controlled by” shall be construed accordingly;
    </span></p>
<p class="abc"><span>1.1.11</span> <span>“<b>Dispute</b>” shall have the meaning ascribed
        to it in Clause 14.2;</span></p>
<p class="abc"><span>1.1.12</span> <span>“<b>Effective Date</b>” shall mean the date of
        this Agreement, or such other date as may be mutually agreed between the Parties in
        writing;</span></p>
<p class="abc"><span>1.1.13</span> <span>“<b>Indemnified Parties</b>” shall have the
        meaning ascribed to it in Clause 10.1;</span></p>
<p class="abc"><span>1.1.14</span> <span>“<b>Indemnity Claim</b>” shall have the meaning
        ascribed to it in Clause 10.1;</span></p>
<p class="abc"><span>1.1.15</span> <span>“<b>Permitted Activities</b>” shall mean
        [insert]; </span></p>
<p class="abc"><span>1.1.16</span> <span>“<b>Person</b>” shall mean and include an
        individual, an association, a corporation, a partnership, a joint venture, a trust, an
        unincorporated organisation, a joint stock company or other entity or organisation,
        including a government or political subdivision, or an agency or instrumentality thereof
        and/or any other legal entity;</span></p>
<p class="abc"><span>1.1.17</span> <span>“<b>Personal Data</b>” shall have the meaning
        ascribed to it in Clause 13.1;</span></p>
<p class="abc"><span>1.1.18</span> <span>“<b>Proprietary Rights</b>” means and includes
        collectively or individually, the following worldwide rights relating to intangible
        property, whether or not filed, perfected, registered or recorded and whether now or
        hereafter existing, filed, issued or acquired:</span></p>
<p class="bde"><span>1.1.18.1</span> <span>patents, patent applications, patent
        disclosures, patent rights, including any and all continuations, continuations-in-part,
        divisions, re-issues, re-examinations, utility, model and design patents or any extensions
        thereof;</span></p>
<p class="bde"><span>1.1.18.2</span> <span>protection of genetic material and plant
        material (also covered as per PPVFR Act). </span></p>
<p class="bde"><span>1.1.18.3</span> <span>rights associated with works of authorship,
        including without limitation, copyrights, copyright applications, copyright
        registrations;</span></p>
<p class="bde" style="margin-bottom:2px;"><span>1.1.18.4</span> <span>rights in trademarks,
        trademark registrations,
        and applications therefor, trade names, service marks, service names, logos, or trade
        dress;</span></p>
<p class="bde" style="margin-bottom:2px;"><span>1.1.18.5</span> <span>rights relating to the
        protection of trade
        secrets;</span></p>
<p class="bde" style="margin-bottom:2px;"><span>1.1.18.6</span> <span>internet domain names,
        internet and world wide
        web (WWW) URLs or addresses;</span></p>
<p class="bde" style="margin-bottom:2px;"><span>1.1.18.7</span> <span>mask work rights, mask
        work registrations and
        applications therefore; and</span></p>
<p class="bde" style="margin-bottom:2px;"><span>1.1.18.8</span> <span>all other intellectual,
        information or
        proprietary rights anywhere in the world including rights of privacy and publicity, rights
        to publish information and content in any media.</span></p>
<p class="abc" style="margin-bottom:2px;"><span>1.1.19</span> <span>“<b>Restricted Parties</b>”
        shall have the
        meaning ascribed to it in Clause 11.1; and </span></p>
<p class="abc"><span>1.1.19</span> <span>“<b>Termination Date</b>” shall have the meaning
        ascribed to it in Clause 11.1.1.</span></p>
<p style="margin-bottom:2px;">1.2 <u>Interpretation</u></p>
<ol type="a">
    <li>Any references, express or implied, to laws, regulations, statutes or statutory
        provisions shall be construed as references to those laws, regulations, statutes or
        provisions as respectively amended or re-enacted or as their application is modified from
        time to time by other provisions (whether before or after the date hereof) and shall include
        any statutes or provisions of which they are re-enactments (whether with or without
        modification) and any orders, regulations, instruments or other subordinate legislation
        under the relevant statute or statutory provision or law or regulation. References to
        sections of consolidating legislation shall wherever necessary or appropriate in the context
        be construed as including references to the sections of the previous legislation from which
        the consolidating legislation has been prepared.
    </li>
    <li>References to any document (including this Agreement) are references to that document as
        amended, consolidated, supplemented, novated, or replaced from time to time.
    </li>
    <li>References herein to Sections, sub-Sections, paragraphs, sub-paragraphs, recitals, and the
        Schedules are to sections, sub-sections, paragraphs, recitals and sub-paragraphs in and the
        schedules to this Agreement unless the context requires otherwise. The Recitals and the
        Schedules to this Agreement shall be deemed to form an integral and operative part of this
        Agreement.
    </li>
    <li>Words denoting the singular shall include the plural and words denoting a particular gender
        shall include all genders.
    </li>
    <li>The words “include” and “including” are to be construed without limitation unless the
        context otherwise requires or unless otherwise specified.
    </li>
    <li>The terms “herein”, “hereof”, “hereto”, “hereunder” and words of similar purport refer to
        this Agreement a Section or Schedule, as a whole, as the case may be.
    </li>
    <li>any reference to “knowledge”, “information” “belief” or “awareness” of any Person shall be
        treated as including any knowledge, information, belief or awareness which the Person would
        have, had the Person made do and reasonable enquiry; further, the knowledge, belief,
        information or awareness of the Company shall be deemed to include the knowledge belief,
        information or awareness of the Company, its Warrantors, officers and directors of the
        Company.
    </li>
    <li>Any reference to a document in “Agreed Form” or in “Agreed Terms” shall mean a document
        agreed (and initialled for such purpose) by the Parties.
    </li>
    <li>References to ‘writing’ shall include any methods of reproducing words in a legible and
        non-transitory form.
    </li>
    <li>Headings, subheadings, titles, subtitles to clauses, sub-clauses and paragraphs are inserted
        for convenience only and shall not affect the construction of this Agreement.
    </li>
    <li>Whenever this Agreement refers to the number of days, such number shall refer to calendar
        days as per the Gregorian calendar, unless otherwise specified.
    </li>
</ol>
<ol type="a" start="12">
    <li>Reference to any document includes any amendment or supplement to, or replacement,
        substitution, or novation of, that document, but disregarding any amendment, supplement,
        replacement, substitution or novation made in breach of this Agreement.
    </li>
    <li>Unless otherwise specified, time periods within or following which any payment is to be made
        or act is to be done shall be calculated by excluding the day on which the period commences
        and including the day on which the period ends and by extending the period to the following
        Business Day if the last day of such period is not a Business Day.
    </li>
    <li>Unless otherwise specified, whenever any payment to be made or action to be taken under this
        Agreement is required to be made or taken on a day other than a Business Day, such payment
        shall be made, or action taken on the next Business Day.
    </li>
    <li>Terms defined elsewhere in this Agreement and not covered in Clause 1.2 shall, unless
        inconsistent with the context or meaning thereof, bear the same meaning as ascribed to them
        throughout this Agreement.
    </li>
</ol>
<br>
<b>
    <p>2. EMPLOYMENT</p>
</b>
<p class="abc" style="margin-bottom:2px;"><span>2.1</span> <span>&nbsp;&nbsp;&nbsp;The Company agrees to employ
        the Executive as
        “<b>{{ getCandidateFullDesignation($sql->JAId) }}</b>" of the Company; and the
        Executive
        accepts such employment and agrees to perform his / her duties for the period and upon the
        terms and conditions set out in this Agreement.</span></p>
<p class="abc"><span>2.2</span> <span>&nbsp;&nbsp;&nbsp;The Executive shall report to
        <b>
            @if ($sql->repchk == 'RepWithoutEmp')
                <strong>{{ getDesignation($sql->reporting_only_desig) }}</strong>
            @else
                <strong>{{ getFullName($sql->A_ReportingManager) }},{{ getEmployeeDesignation($sql->A_ReportingManager) }}</strong>
            @endif
        </b> (“Manager”), the person in
        being may change from time to time and shall perform his / her duties under its supervision
        and direction, on the terms and conditions set out in this Agreement. The Employee shall at
        all times keep the Manager promptly and fully informed (in writing if so requested) of his /
        her conduct of the business or affairs of the Company and also provide such further
        information, written records and/or explanation as the Manager may require.</span></p>
<p class="abc" style="margin-bottom:2px;"><span>2.3</span> <span>&nbsp;&nbsp;&nbsp;


        @if ($sql->TempS == 1 && $sql->FixedS == 0)
            For initial {{ $months_word[$sql->TempM] }} months, The Executive’s place of
            employment shall
            be at
            <strong>{{ getHq($sql->T_LocationHq) }}({{ getHqStateCode($sql->T_StateHq) }})</strong>
            @if ($sql->T_StateHq1 != null || $sql->T_StateHq1 != '')
                or
                <strong>{{ getHq($sql->T_LocationHq1) }} ({{ getHqStateCode($sql->T_StateHq1) }})
                </strong>
            @endif
            India. The Executive may be required to (i) relocate to other locations in India (ii)
            undertake such travel in India; and/or, (iii) undertake travel overseas, from time to
            time,
            as may be necessary in the interests of the Company's business.
        @else
            The Executive’s principal place of employment shall
            be at
            <strong>{{ getHq($sql->F_LocationHq) }}({{ getHqStateCode($sql->F_StateHq) }})</strong>,
            India. The Executive may be required to (i) relocate to other locations in India (ii)
            undertake such travel in India; and/or, (iii) undertake travel overseas, from time to
            time,
            as may be necessary in the interests of the Company's business.
        @endif
    </span></p>
<p class="abc"><span>2.4</span> <span>&nbsp;&nbsp;&nbsp;The Executive shall work during the regular
        business hours of the Company as per its policy in effect from time to time. Notwithstanding
        the foregoing, the Executive shall work such additional hours as may be necessary for the
        Executive to perform and discharge his / her duties effectively and otherwise in accordance
        with the Company's policies pertaining to the same. The Executive shall not be entitled to
        receive any additional remuneration for work done outside the regular business hours of the
        Company, during the period of employment with the Company. </span></p>
<b>
    <p>3. AGREED TERM </p>
</b>
<p style="text-align: justify">The term of this Agreement shall commence on the Effective Date.
    Subject to Clause 12 of this Agreement, the Executive is employed from the Effective Date
    (“Agreed Term”). The Agreed Term maybe extended for such other period as maybe agreed in writing
    between the Parties and such other period would accordingly be construed as the Agreed Term</p>
<b>
    <p style="margin-bottom:2px;">4. DUTIES </p>
</b>
<p style="text-align: justify" style="margin-bottom:2px;">The Executive shall have the following
    duties and objectives during
    the Agreed Term or any renewal thereof:</p>
<p class="abc" style="margin-bottom:2px;"><span>4.1</span> <span><u>Specific Duties /
            Objectives</u></span></p>
<p class="abc"><span> &nbsp; &nbsp; &nbsp;</span> <span>&nbsp;&nbsp;&nbsp;The Executive shall be
        responsible for the specific duties set out in <b>Schedule 2.</b> As part of the specific
        duties under
        this Agreement, the Executive agrees to assist the Company in the transmission and conveying
        of such responsibilities and duties, as may be determined by the Company, to such other
        employee(s) nominated by the Company.</span></p>
<p class="abc"><span>4.2</span> <span><u>General Duties</u></span></p>
<p class="bde"><span>4.2.1</span> <span>&nbsp;&nbsp;&nbsp;&nbsp;The Executive shall serve the Company
        faithfully and to the best of his / her ability, and devote his / her full time, attention,
        skill, and efforts to fulfil the obligations set out in this Agreement and attend to
        relevant affairs during working hours every day (and outside these hours as may be
        reasonably required), subject to holidays, leave and other paid time off taken in accordance
        with statutory entitlement of the Executive under applicable law. </span></p>
<p class="bde"><span>4.2.2</span> <span>&nbsp;&nbsp;In addition, the Executive shall have
        such duties consistent with the Executive’s job title as are reasonably assigned to the
        Executive by the Company, from time to time. </span></p>
<p class="bde"><span>4.2.3</span> <span>&nbsp;The Executive agrees to comply with the
        Company’s decision should it consider it necessary or appropriate to change the Executive’s
        job title, reporting relationships, job duties and responsibilities, the jurisdiction where
        the Executive is expected to perform his / her duties (despite location of his / her
        residence) based on the Executive’s performance or the Company’s business requirements. Any
        such change shall not be deemed to violate the terms of the Agreement or constitute any
        basis for constructive or involuntary termination of employment, provided that the
        Executive’s fixed remuneration for services rendered to the Company is not substantially
        reduced </span></p>
<p class="bde"><span>4.2.4</span> <span>&nbsp;The Executive shall comply with all written
        policies of the Company that are issued pursuant to this Agreement, from time to time.
    </span></p>
<p class="bde"><span>4.2.5</span> <span>&nbsp;The Executive shall work with high
        standards of initiative, efficiency, and economy. Executive shall serve to the utmost of his
        / her ability and use best endeavours to promote and protect the interests of the Company
        and its Affiliates. Executive will not resort to any action that would hamper the
        functioning of the Company or its Affiliates nor would the Executive involve in any activity
        which may be detrimental to their interest. </span></p>
<p class="bde"><span>4.2.6</span> <span>&nbsp; The Executive shall not perform services
        for, or take an active management role in, or become a member of the board of directors or
        similar body for, any other corporation, firm, entity, or Person without the prior written
        approval of the Company. Should the Executive receive written consent under this Clause to
        conduct any such external activity, he / she shall not utilize the assets, resources and
        time of the Company for the same. </span></p>
<p class="bde"><span>4.2.7</span> <span>&nbsp; The Executive shall act in accordance with
        all laws, ordinances, regulations, or rules of any governmental, regulatory or
        administrative body, agent or authority, any court or judicial authority, or any public,
        private or industry regulatory authority.</span></p>
<p class="bde"><span>4.2.8</span> <span>&nbsp; The Executive hereby represents to the
        Company that in the performance of his / her duties hereunder the Executive will not use any
        information that the Executive is not legally or contractually permitted to disclose to the
        Company. The Executive represents that any confidentiality, trade secret or similar
        agreement to which he / she  is a party or otherwise bound will not interfere with the
        effective performance by the Executive of his duties hereunder.</span></p>
<p class="bde"><span>4.2.9</span> <span>&nbsp; The Executive shall not, without the prior
        written consent of the Company (which may be given or withheld at the absolute discretion of
        the Company), whether directly or indirectly, publish any opinion, fact or material or
        deliver any lecture or address or communicate with any representative of the media or at any
        public forum, in relation to any Confidential Information. The Executive shall not have the
        right or authority to make any representation, contract or commitment for or on behalf of
        the Company except in accordance with the approvals granted by the Board. </span></p>
<p class="bde"><span>4.2.10</span> <span>&nbsp;Upon the receipt of reasonable notice from
        the Company, the Executive agrees that while employed by the Company or thereafter, the
        Executive will reasonably respond and provide information with regard to matters in which
        the Executive has knowledge as a result of the Executive’s employment/engagement with the
        Company and its Affiliates, and will provide reasonable assistance to the Company and its
        representatives in defence of any claims that may be made against the Company, and will
        reasonably assist the Company in the prosecution of any claims that may be made by the
        Company, to the extent that such claims may relate to the period of the Executive’s
        employment with the Company and its Affiliates. Executive agrees to promptly inform the
        Company if the Executive becomes aware of any lawsuits involving such claims that may be
        filed or threatened against the Company. The Executive also agrees to promptly inform the
        Company, if the Executive is asked to assist in any investigation of the Company or their
        actions, regardless of whether a lawsuit or other proceeding has then been filed with
        respect to such investigation and shall not do so unless legally required. </span></p>
<b>
    <p>5. COMPENSATION</p>

</b>
<p class="abc"><span>5.1</span> <span><u>Salary and Benefits</u></span></p>
<p class="bde"><span>5.1.1</span> <span>&nbsp;As compensation for the performance of the
        Executive’s duties, covenants and obligations under this Agreement, the Executive’s
        remuneration and benefits are set out in <u><b>Schedule 1</b></u> hereto
        <b>(“Compensation”)</b>. The salary shall be paid in accordance with the Company’s normal
        payroll procedures and policies, but not less frequently than in monthly instalments (in
        arrears). The Compensation may be revised by the Board from time to time.</span></p>
<p class="bde"><span>5.1.2</span> <span>&nbsp;All payments or benefits under this
        Agreement are subject to any applicable taxes, withholdings, or other permissible
        deductions. </span></p>
<p class="bde"><span>5.1.3</span> <span>&nbsp;&nbsp;The Executive shall be entitled to
        participate in all welfare and benefit plans, policies and programmes that the Company
        maintains or establishes and makes available to its employees, if any, to the extent that
        Executive’s position, tenure, salary, age, health and other qualifications make the
        Executive eligible to participate, provided that the Company shall have no obligation to
        maintain or continue any such plans, policies or programmes. </span></p>

<p class="abc"><span>5.2</span> <span><u>Expense</u></span></p>
<p class="bde"><span>5.2.1</span> <span>&nbsp;The Company shall reimburse (or procure the
        reimbursement of) all reasonable expenses wholly properly and necessarily incurred by the
        Executive in the course of the performance of duties under this Agreement, subject to
        production of invoice or other appropriate evidence of payment. The Executive shall raise
        the claims in respect of the expenses incurred by it, on an ongoing basis, within a period
        of 30 (Thirty) days from incurring such expenditure and all such invoices shall be
        accompanied by such supporting documents as may be required or as may be communicated by the
        Company to the Executive. </span></p>
<p class="bde"><span>5.2.2</span> <span>&nbsp;The Executive shall abide by the Company's
        policies on expenses as communicated to him from time to time.</span></p>
<b>
    <p>6. SURVEILLANCE</p>

</b>
<p style="text-align: justify">The Company reserves the right to monitor its employees using
    various
    security measures including but not limited to closed circuit television systems. These may be
    installed on the Company’s premises. </p>
<b>
    <p>7. CONFIDENTIAL INFORMATION</p>

</b>
<p class="abc"><span>7.1</span> <span>&nbsp;The Executive recognizes that during the
        course of his / her employment with Company and/or its Affiliates, he / she has been and
        shall be privy to certain Confidential Information. In consideration of the benefits accrued
        and accruing to the Executive in the course of his / her employment, the Executive agrees
        and undertakes:</span></p>
<p class="bde"><span>7.1.1</span> <span> &nbsp;&nbsp;&nbsp;that the Executive shall not,
        without the prior written permission of Company, directly or indirectly disclose or cause to
        be disclosed any Confidential Information to any third party;</span></p>
<p class="bde"><span>7.1.2</span> <span> &nbsp;&nbsp;that the Executive shall take all
        steps as may be reasonably necessary to protect the integrity of the Confidential
        Information and to ensure against any unauthorized disclosure thereof;</span></p>
<p class="bde"><span>7.1.3</span> <span> &nbsp;&nbsp;that the Executive shall promptly
        inform the Company of any potential or accidental disclosure of Confidential Information and
        shall take all steps, together with the Company, to retrieve and protect the Confidential
        Information; and</span></p>
<p class="bde"><span>7.1.4</span> <span> &nbsp;&nbsp;that the Executive shall use the
        Confidential Information only for the purpose for which it was provided and for the benefit
        of the Company and its Affiliates and shall not profit from the same in any unauthorised
        manner whatsoever.</span></p>
<p class="abc"><span>7.2</span> <span>&nbsp;&nbsp;The Executive acknowledges and agrees
        that the Company has the right to inform his future employers of the presence of this Clause
        7 and the resultant obligations imposed on the Executive. The obligations contained in this
        Clause 7 shall continue to remain binding upon the Executive even after the cessation of his
        employment with the Company.</span></p>

<b>
    <p>8. INTELLECTUAL PROPERTY RIGHTS </p>

</b>
<p class="abc" style="margin-bottom:2px;"><span>8.1</span> <span>&nbsp;In view of the fact that
        it is the
        Executive’s responsibility to further the interests of the Company and its Affiliates, the
        Executive shall forthwith disclose to the Company every discovery, invention, improvement,
        design and secret process and other Proprietary Rights made, developed or discovered by him
        in connection with or in any way relating to the Business (whether alone or with any other
        Person or Persons) at any time whether before or after the date hereof, but during his
        employment with the Company, whether capable of being patented or registered or not (and
        whether or not made or discovered in the course of his employment hereunder) in connection
        with or in any way affecting or relating to the business of the Company or capable of being
        used or adapted for use therein or in connection therewith and the Parties agree that all
        such information and materials shall belong to and be the absolute property of the Company.
        The Executive hereby waives all his moral rights on any copyright work originated,
        conceived, written or made by him in connection with or in any way relating to the Business
        (either alone or with others) and he / she agrees not to claim that any treatment,
        exploitation or use of the said works infringes such moral rights (including but not limited
        to right to be indemnified, right to object derogatory treatment and against false
        attribution).</span></p>
<p class="abc" style="margin-bottom:2px;"><span>8.2</span> <span>&nbsp;The Executive shall have
        no right, title, or
        interest whatsoever over the Proprietary Rights/ intellectual property rights created or
        developed by the Executive in the course of his / her employment with the Company and/or its
        Affiliates since <b>{{ date('d-m-Y', strtotime($sql->JoinOnDt)) }}</b> and shall not be
        entitled to use or exploit the same in any manner whatsoever other than in the course of and
        for the purposes of his / her employment with the Company and its Affiliates. This Agreement
        shall further operate as a worldwide perpetual, written assignment in favour of the Company
        of any right, title, or interest that the Executive may have in respect of such intellectual
        property. It is understood that all intellectual property created by the Executive in the
        course of his / her employment with the Company and its Affiliates shall be considered as
        <b> "work made for hire"</b> and shall irrevocably vest with Company worldwide and in
        perpetuity.
        The Executive will execute all documents as may be required by the Company and otherwise
        fully cooperate with Company in the process and registration of all such rights, if deemed
        necessary by the Company. </span></p>
<p class="abc" style="margin-bottom:2px;"><span>8.3</span> <span>&nbsp;The Executive
        acknowledges that he / she is
        not acquiring any right, title, or interest in the Company’s trademarks, trade names,
        service marks, copyrights, patents, ideas, concepts, designs, specifications, models,
        processes, software systems, technologies, and other intellectual property owned, acquired
        or developed by the Company, its Affiliates, and their employees, contractors, or
        consultants.</span></p>
<b>
    <p>9. REPRESENTATIONS AND WARRANTIES </p>

</b>
<p class="abc"><span>9.1</span> <span>&nbsp;The Executive hereby represents, warrants and
        undertakes that:</span></p>
<p class="bde"><span>9.1.1</span> <span>&nbsp;&nbsp;The execution of this Agreement by
        him with the Company will not result in breach of any terms and conditions of any agreements
        or arrangements or infringe any statutory, contractual or other rights of any third parties,
        or constitute default under the laws of India or violate any rule, regulation or law of any
        government or any order, judgment or decree of any court or government body.</span></p>
<p class="bde"><span>9.1.2</span> <span>&nbsp;The Executive shall discharge the duties
        assigned to him in the course of his / her employment with greatest sincerity and diligence
        and shall at all times exercise his / her best efforts to protect and further the interests
        of the Company and its Affiliates.</span></p>
<p class="bde" style="margin-bottom:2px;"><span>9.1.3</span> <span>&nbsp;&nbsp;The Executive
        shall, at all times
        during the subsistence of his / her employment, act in compliance with all applicable rules,
        regulations and policies of the Company and its Affiliates.</span></p>
<p class="bde" style="margin-bottom:2px;"><span>9.1.4</span> <span>&nbsp;&nbsp;The Executive
        has not been convicted
        of any offence by any court of law and is not a party to any proceedings pending before or
        likely to be initiated before or by any court, tribunal, government agency or similar
        statutory body.</span></p>

<p class="abc"><span>9.2</span> <span>&nbsp;&nbsp;It is hereby expressly understood that
        the Company is entering into this Agreement based on the understanding that the information
        and documents provided by the Executive to the Company are correct, true and complete in all
        respects. If it is discovered at any time in the future that the information and/or
        documents provided by the Executive is or was incorrect, untrue or false in any material
        respect or if it is discovered that any material particulars or information has been
        deliberately withheld or suppressed, Company shall be entitled to terminate the employment
        of the Executive forthwith without being required to give any notice.</span></p>
<p class="abc"><span>9.3</span> <span>&nbsp;The Executive shall carefully read the human
        resource policies and other manuals of the Company to understand the Company policies on
        leaves, holidays, code of conduct, disciplinary actions, etc., and provide an
        acknowledgement of its acceptance. The Executive shall at all times comply with the policies
        and procedures laid down by the Company from time to time and any amendments/modifications
        thereto. If the Executive fails to provide an acknowledgement on the Effective Date, it
        shall be deemed as Executive’s acceptance to the Employee Policy and other manuals shared
        with the Executive prior to Effective Date.</span></p>
<b>
    <p style="margin-bottom:2px;">10. INDEMNIFICATION </p>

</b>
<p class="abc" style="margin-bottom:2px;"><span>10.1</span> <span>&nbsp;The Executive agrees
        and undertakes, to
        indemnify, defend and hold harmless (at all times whether during or after termination or
        expiry of this Agreement), the Company and each of its Affiliates, as applicable, and all of
        their directors, officers, employees, representatives, and advisors (collectively, the
        <b>“Indemnified Parties”</b>) promptly upon demand at any time and from time to time, for
        any and all claims, losses, damages or liabilities, suffered or incurred by the Indemnified
        Parties (together, an <b>“Indemnity Claim”</b>) arising out of or in connection with:</span>
</p>
<p class="bde" style="margin-bottom:2px;"><span>10.1.1</span> <span>&nbsp;&nbsp;an inaccuracy,
        misrepresentation, or
        breach of any of the Executive’s representation and warranties set out in this
        Agreement;</span></p>
<p class="bde" style="margin-bottom:2px;"><span>10.1.2</span> <span>&nbsp;&nbsp;breach of any
        of the covenants or
        obligations contained in this Agreement, including negligence or misconduct; </span></p>
<p class="bde" style="margin-bottom:2px;"><span>10.1.3</span> <span>&nbsp;&nbsp;any and all
        costs and expenses
        incurred by an Indemnified Party in respect of an Indemnity Claim; </span></p>
<p class="bde" style="margin-bottom:2px;"><span>10.1.4</span> <span>&nbsp;&nbsp;breach of
        confidentiality obligation
        under this Agreement;</span></p>
<p class="bde" style="margin-bottom:2px;"><span>10.1.5</span> <span>&nbsp;&nbsp;breach of
        third-party intellectual
        property rights; or </span></p>
<p class="bde"><span>10.1.6</span> <span>&nbsp;any claim by any third party, including a
        governmental authority, against an Indemnified Party arising out of any of the circumstances
        mentioned in sub-clauses 10.1.1 to 10.1.5 above.</span></p>
<p class="abc"><span>10.2</span> <span>&nbsp;The rights of an Indemnified Party pursuant
        to this Clause 10 shall be in addition to and not exclusive of, and shall be without
        prejudice to, any other rights and remedies available to such Indemnified Party at equity or
        law including the right to seek specific performance, rescission, restitution, or other
        injunctive relief, none of which rights or remedies shall be affected or diminished
        thereby.</span></p>
<b>
    <p>11. NON-COMPETITION AND NON-SOLICITATION UNDERTAKING</p>

</b>
<p class="abc"><span>11.1</span> <span><b>Non-compete</b></span></p>
<p class="bde"><span> &nbsp;&nbsp;&nbsp;&nbsp;</span> <span>The Executive undertakes
        that:</span></p>
<p class="abc"><span>(a) &nbsp;</span> <span>other than the Permitted Activities, he /
        she shall not (and shall cause his / her Affiliates not to), for a period of: (A) 5 (Five)
        years from the Effective Date; or (B) 3 (Three) years from the date of termination of this
        Agreement, whichever is later <b>(Restricted Period)</b>, singly or jointly, directly or
        indirectly, in any capacity, whether through partnership or as a shareholder, joint venture
        partner, collaborator, consultant or agent or in any other manner whatsoever, whether for
        profit or otherwise: </span></p>
<p class="bde"><span>&nbsp;&nbsp;(i)</span> <span>&nbsp;&nbsp;carry on or participate in
        (whether as a partner, shareholder, principal, agent, director, employee or consultant)
        and/or invest in any business and/or activity which is the same as, or similar to, the
        present Business or such other business that the Company may undertake in future (each, a
        “Covered Activity”) other than through the Company including in the Competing
        Business;</span></p>
<p class="bde"><span>&nbsp;&nbsp;(ii)</span> <span>&nbsp;&nbsp;render any services to a
        competitor of the Company or enter into employment with any of the competitors of the
        Company;</span></p>
<p class="bde"><span>&nbsp;&nbsp;(iii)</span> <span>&nbsp;&nbsp;solicit or influence or
        attempt to influence any client, customer or other Person to direct its purchase of the
        products and/or services of the Company to itself or any competitor;</span></p>
<p class="bde"><span>&nbsp;(iv)</span> <span>&nbsp;solicit or attempt to influence any
        Person, employed or engaged by the Company (whether as an employee consultant, advisor or
        distributor or in any other manner) to terminate or otherwise cease such employment or
        engagement with the Company or become the employee of or directly or indirectly offer
        services in any form or manner to himself or any competitor of the Company; </span></p>
<p class="bde"><span>&nbsp;&nbsp;(v)</span> <span>&nbsp;&nbsp;engage in any activity
        that conflicts with its obligations in terms of this Agreement; </span></p>
<p class="bde"><span>&nbsp;&nbsp;(vi)</span> <span>&nbsp;&nbsp;undertake or participate
        in (whether as a partner, shareholder, principal, agent, director, employee or consultant)
        and/ or invest in a new business without bringing the same to the notice of the Board;
    </span></p>
<p class="bde"><span>&nbsp;&nbsp;(vi)</span> <span>&nbsp;&nbsp;transfer, use or disclose
        any customer database or Proprietary Rights of the Company or other information pertaining
        to the customers or suppliers of the Company, other than for the bona fide business needs of
        the Company, to any third-party or competitor of the Company; or</span></p>
<p class="bde"><span>&nbsp;(vii)</span> <span>receive any financial benefit from any
        Covered Activity, whether as an employer, proprietor, partner, shareholder, investor,
        director, officer, employee, consultant, agent, representative or otherwise.</span></p>

<p class="abc"><span>(b) &nbsp;</span> <span>he / she shall not use any of the
        Proprietary Rights of the Company or its Affiliates; and</span></p>
<p class="abc"><span>(c) &nbsp;</span> <span>he / she shall not challenge the validity
        or enforceability of any of the Business IP. </span></p>
<p class="abc"><span>11.2</span> <span><b>Non-solicitation</b></span></p>
<p class="bde"><span> &nbsp;&nbsp;&nbsp;&nbsp;</span> <span>During the Restricted
        Period, the Executive shall not whether directly or indirectly:</span></p>

<p class="abc"><span>(a) &nbsp;</span> <span>solicit, entice away from the Company for
        any purpose, any customer, client, supplier, business associates, director, officers or
        employee of the Company; or</span></p>
<p class="abc"><span>(b) &nbsp;</span> <span>solicit, or cause any customer, client,
        supplier or business associates of the Company to either: </span></p>
<p class="bde"><span>&nbsp;&nbsp;(i)</span> <span>&nbsp;&nbsp;terminate their agreements
        or arrangements with the Company; </span></p>
<p class="bde"><span>&nbsp;&nbsp;(ii)</span> <span>&nbsp;&nbsp;reduce the supply of the
        goods or services, or to increase the cost of goods or services; or </span></p>
<p class="bde"><span>(iii)</span> <span>&nbsp;vary adversely the terms upon which such
        customer, client, supplier or business associates conducts business with the Company.</span>
</p>

<p class="abc"><span>11.3</span> <span>It is hereby agreed between the Parties if any of
        the restrictions is void but would be valid if some part of the restriction were deleted
        and/or modified, the restriction in question shall apply with such deletion and/or
        modification as may be necessary to make it valid.</span></p>
<p class="abc"><span>11.4</span> <span>The Executive acknowledges and agrees that each
        of the prohibitions and restrictions contained in this Clause 11: (i) will be read and
        construed and will have effect as a separate severable and independent prohibition or
        restriction and will be enforceable accordingly; (ii) are fair and reasonable as to period,
        scope, territorial limitations and subject matter for the legitimate protection of the
        business and goodwill of the Company; and (iii) does not prevent the Executive or his / her
        Affiliates from earning a livelihood and in no event, would prevent the Executive or his /
        her Affiliates from otherwise engaging in any trade or vocation. However, in the event that
        such restriction shall be found to be void but would be valid if some part thereof was
        deleted or the scope, period or area of application were reduced, the above restriction
        shall apply with the deletion of such words or such reduction of scope, period or area of
        application as may be required to make the restrictions contained in this clause valid and
        effective. Provided however, that on the revocation, removal or diminution of the law or
        provisions, as the case may be, by virtue of which the restrictions contained in this clause
        were limited as provided hereinabove, the original restrictions would stand renewed and be
        effective to their original extent, as if they had not been limited by the applicable law or
        provisions revoked.</span></p>
<p class="abc"><span>11.5</span> <span>The Executive acknowledges and agrees that the
        covenants and obligations with respect to non-competition and non-solicitation as set forth
        above in Clause 11.1 shall not be construed to be a restraint of trade against the Executive
        and relate to special, unique and extraordinary matters, and that a violation of any of the
        terms of such covenants and obligations will cause the Company as the case may be,
        irreparable injury. Each of such covenants contained in this clause shall be construed as a
        separate covenant and if, in any judicial proceeding, a court shall refuse to enforce any of
        the separate covenants of this clause, then such covenant shall be deemed included herein
        only to the extent enforceable as permitted under the applicable laws for the purpose of
        such proceeding or any other judicial proceeding to the extent necessary to permit the
        remaining covenants to be enforced. </span></p>
<p class="abc"><span>11.6</span> <span>The Parties agree and acknowledge that no
        separate consideration is payable for the rights and obligations contained in this Clause
        11, and the mutual covenants in this Agreement are deemed to be adequate consideration and
        that the restrictions contained in this Clause 11 are fair and reasonable as to period,
        scope, territorial limitations and necessary for the legitimate protection of the business
        and goodwill of the Company. </span></p>
<b>
    <p>12. TERMINATION</p>

</b>
@php
    if ($sql->Department == 15 || $sql->Department == 17 || $sql->Department == 3 || $sql->Department == 2 || $sql->Department == 10) {
        $noticePeriod = '3 (three)';
    } else {
        $noticePeriod = '1 (one)';
    }
@endphp
<p class="abc"><span>12.1</span> <span><u>Notice Period</u></span></p>
<p class="abc"><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span>Either Party may
        terminate this Agreement by giving to the other, <b>{{ $noticePeriod }}</b>
        Month’s notice in writing or in case of the Company, the payment of the Compensation in lieu
        thereof.</span></p>
<p class="abc"><span>12.2</span> <span><u>Death</u></span></p>
<p class="abc"><span> &nbsp;&nbsp;&nbsp;&nbsp;</span> <span>&nbsp;This Agreement shall
        terminate prior to the expiration of the Agreed Term or of any extension thereof,
        immediately upon the date of the Executive’s death.</span></p>

<p class="abc"><span>12.3</span> <span><u>Termination For Cause </u></span></p>
<p class="abc"><span> &nbsp;&nbsp;&nbsp;</span> <span>Termination for Cause, as stated
        in Clause 1.1.4 in this Agreement, shall be effective immediately upon the provision of a
        notice in writing by the Company to the Executive without the requirement for the
        application of a Notice Period as stated in Clause 12.1 in this Agreement</span></p>

<p class="abc"><span>12.4</span> <span><u>Consequences of Termination</u></span></p>
<p class="abc"><span> &nbsp;&nbsp;&nbsp;</span> <span>&nbsp;&nbsp;&nbsp;On termination
        of this Agreement (however arising) and/or, if required by the Company, the Executive
        shall:</span></p>

<p class="bde"><span>12.4.1</span> <span>resign immediately without compensation from
        any office including the office of the director of the Company and/or its Affiliates (if
        applicable) that he / she holds in or on behalf of the Company and/or its Affiliates (if
        applicable);</span></p>
<p class="bde" style="margin-bottom:2px;"><span>12.4.2</span> <span>immediately deliver to the
        Company all
        documents, books, materials, records, correspondence, papers and information (on whatever
        media and wherever located) relating to the business or affairs of the Company and/it
        Affiliates or their business contacts, any keys, credit card, laptop, mobile phone and any
        other property of the Company and/or its Affiliates, which is in his / her possession or
        under his / her control including any Confidential Information. The Executive acknowledges
        that he / she will not retain any copies, duplicates, reproductions or excerpts of such
        materials or documents;</span></p>
<p class="bde" style="margin-bottom:2px;"><span>12.4.3</span> <span>irretrievably delete any
        information (including
        Confidential Information) relating to the business of the Company and/or its Affiliates
        stored on any magnetic or optical disk or memory and all matter derived from such sources
        which is in his / her possession or under his / her control;</span></p>
<p class="bde" style="margin-bottom:2px;"><span>12.4.4</span> <span>provide a signed statement
        that he / she has
        complied fully with his / her obligations under this Clause 12.4 together with such
        reasonable evidence of compliance as the Company may request. </span></p>
<p class="bde" style="margin-bottom:2px;"><span>12.4.5</span> <span>Notwithstanding the
        foregoing, the provisions
        of Clauses 4.2.10 (General Duties), 7 (Confidential Information), 8 (Intellectual Property
        Rights), 10 (Indemnification), 11 (Non-Competition and Non-Solicitation Undertaking), 12
        (Termination), 13 (Data Protection) and 14 (Miscellaneous) hereof and any provision which by
        its nature is intended to survive termination, shall survive the expiration or termination
        of this Agreement for any reason. The termination of this Agreement shall in no event
        terminate or prejudice the rights, obligations and liabilities of the Parties that have
        accrued or arisen prior to the date of termination. </span></p>
<p class="bde" style="margin-bottom:2px;"><span>12.4.6</span> <span>In the event of termination
        of the employment
        of the Executive, the Executive shall not be entitled to any non-compete fee or severance
        pay. Further, on termination of the Executive’s employment, for any reason, the Company will
        be entitled to deduct from amounts owed to the Executive, any amounts that the Executive
        owes or may owe to the Company under any agreement or arrangement. </span></p>

<b>
    <p>13. DATA PROTECTION </p>

</b>

<p class="abc"><span>13.1</span> <span>The Executive hereby consents to the Company
        (including its Affiliates) to process data relating to the Executive (“Personal Data”) at
        any time (whether before, during or after the employment) for the following purposes:</span>
</p>

<p class="bde"><span>13.1.1</span> <span>performing its obligations under this Agreement
        (including administering and maintaining personnel records, remuneration, payroll,
        appraisals, pension, insurance and other benefits, tax and national insurance obligations,
        wherever applicable);</span></p>
<p class="bde"><span>13.1.2</span> <span>protecting the legitimate interests of the
        Company or its Affiliates including conducting business, any sickness policy, working time
        policy, investigating acts or defaults (or alleged or suspected acts or defaults) of the
        Executive, security, management forecasting or planning and negotiations with the Executive;
        and</span></p>
<p class="bde"><span>13.1.3</span> <span>processing in connection with any merger, sale
        or acquisition of a company or business in which the Company or its Affiliates is involved.
    </span></p>

<p class="abc"><span>13.2</span> <span>&nbsp;&nbsp;&nbsp;The Personal Data referred to above will include
        but not be limited to Executive’s name, address, other contact details, date of birth,
        nationality, requisite tax details, health and sickness records, details of next of kin/
        other family members and other such information. Personal Data is kept securely and is only
        accessed by those who need to access it for the purposes set out above. </span></p>
<p class="abc"><span>13.3</span> <span>&nbsp;&nbsp;&nbsp;If Executive provides Personal Data relating to
        third parties such as the Executive’s spouse and children, the Executive confirms that
        Executive has the requisite authority to do so and that such Personal Data may be processed
        by the Company or its Affiliates for any of the purposes set out above.</span></p>
<p class="abc"><span>13.4</span> <span>&nbsp;&nbsp;&nbsp;Executive’s Personal Data may be shared with
        others, including the police, revenue authorities, the trustees of any Company pension fund
        that the Executive is a member of and any other personal or organisation who has the legal
        right to access such Personal Data including the Affiliates of the Company.</span></p>
<p class="abc"><span>13.5</span> <span>&nbsp;&nbsp;&nbsp;Executive shall have the right to access and
        update the Executive’s Personal Data by contacting the Company’s human resources
        department.</span></p>

<b>
    <p>14. MISCELLANEOUS </p>
</b>
<p class="abc"><span>14.1</span> <span><u>Governing Law</u></span></p>
<p class="abc"><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span>&nbsp;&nbsp;&nbsp;This Agreement
        shall be governed and constituted in accordance with applicable laws of India. Subject to
        Clause 14.2, the courts at Raipur, India shall have the exclusive jurisdiction. </span></p>
<p class="abc"><span>14.2</span> <span><u>Dispute Resolution </u></span></p>
<p class="abc"><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span>&nbsp;&nbsp;&nbsp;In case of any
        difference, dispute, controversy or claim (“Dispute”) which may arise between the Company
        and the Executive out of or in relation to or in connection with this Agreement, including
        the breach, termination, effect, validity, interpretation or application of the terms of
        service or as to the rights, duties or liabilities hereunder, the courts of Raipur shall
        have jurisdiction. The law to be applied shall be the laws of India. </span></p>
<p class="abc"><span>14.3</span> <span><u>Entire Agreement </u></span></p>
<p class="abc"><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span>&nbsp;&nbsp;&nbsp;This Agreement
        constitutes the entire agreement of the Parties relating to the subject matter hereof and
        supersedes any and all prior agreements, including letters of intent and agreements, either
        oral or in writing, between the Parties with respect to the subject matter herein. </span>
</p>
<p class="abc"><span>14.4</span> <span><u>Withholding Taxes </u></span></p>
<p class="abc"><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span>&nbsp;&nbsp;&nbsp;All taxes in
        relation to the Executive’s salary, perquisites, allowances and benefits payable to the
        Executive herein will be borne solely and exclusively by the Executive. The Company may
        withhold from any salary and any benefits payable under this Agreement all federal, state,
        city or other taxes as shall be required pursuant to any law or governmental regulation or
        ruling. </span></p>
<p class="abc" style="margin-bottom:2px;"><span>14.5</span> <span><u>Leave</u></span></p>
<p class="abc" style="margin-bottom:2px;"><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    <span>You will be
        eligible for leaves as per the leave policy of the Company, as amended from time to
        time.</span>
</p>
<p class="abc" style="margin-bottom:2px;"><span>14.6</span> <span><u>Waiver and Amendments
        </u></span></p>
<p class="abc" style="margin-bottom:2px;"><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    <span>&nbsp;&nbsp;&nbsp;No amendment,
        modification or discharge of this Agreement shall be valid or binding unless set forth in
        writing and duly executed by the Parties. Unless otherwise expressly provided in this
        Agreement, no waiver shall be valid unless given in writing by the Party or Parties from
        whom such waiver is sought. Any such waiver shall constitute a waiver only with respect to
        the specific matter described in such writing and shall in no way impair the rights of the
        Party granting such waiver in any other respect or at any other time. Neither the waiver by
        any of the Parties of a breach of or a default under any of the provisions of this
        Agreement, nor the failure by any of the Parties, on one or more occasions, to enforce any
        of the provisions of this Agreement or to exercise any right or privilege hereunder, shall
        be construed as a waiver of any other breach or default of a similar nature, or as a waiver
        of any of such provisions, rights or privileges hereunder.</span>
</p>

<p class="abc" style="margin-bottom:2px;"><span>14.7</span> <span><u>Assignment </u></span></p>
<p class="abc" style="margin-bottom:2px;"><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    <span>&nbsp;&nbsp;&nbsp;This Agreement
        may not be assigned, delegated, or subcontracted in whole or in part by either Party without
        first obtaining the other Party’s express written consent; provided, however, that the
        Company may assign, delegate, or subcontract this Agreement in whole or in part to a present
        or future Affiliate without obtaining the Executive’s express written consent, provided that
        such present or future Affiliate expressly assumes the obligations of the Company under this
        Agreement, and provided further, that in the event of a sale or other transfer of all or
        substantially all of the assets or business of the Company with or to any other
        individual(s) or entity that is not an Affiliate, this Agreement shall be binding upon and
        inure to the benefit of such successor and such successor shall discharge and perform all
        the promises, covenants, duties, and obligations of the Company hereunder. </span>
</p>
<p class="abc" style="margin-bottom: 2px;"><span>14.8</span> <span><u>Injunctive Relief
        </u></span></p>
<p class="abc" style="margin-bottom: 2px;"><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    <span>&nbsp;&nbsp;&nbsp;The Executive
        agrees that the Company shall be entitled to an injunction, restraining order, right for
        recovery, suit for specific performance or such other equitable relief as a court of
        competent jurisdiction may deem necessary or appropriate to restrain the Executive from
        committing any violation or to enforce the performance of the covenants, warranties and
        obligations contained in this Agreement. These injunctive remedies are cumulative and are in
        addition to any other rights and remedies that the Company may have at law or in equity,
        including without limitation a right for damages. </span>
</p>
<p class="abc" style="margin-bottom:2px;"><span>14.9</span> <span><u>Severability </u></span>
</p>
<p class="abc" style="margin-bottom:2px;"><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    <span>&nbsp;&nbsp;&nbsp;Each and every
        obligation under this Agreement shall be treated as a separate obligation and shall be
        severally enforceable as such and in the event of any obligation or obligations being or
        becoming unenforceable in whole or in part. To the extent that any provision or provisions
        of this Agreement are unenforceable such provision or provisions shall be deemed to be
        deleted from this Agreement and any such deletion shall not affect the enforceability of the
        remainder of this Agreement not so deleted provided the fundamental terms of this Agreement
        are not altered. To the extent permitted by applicable law, the Parties agree in good faith
        to replace any such illegal, void or unenforceable provision by a lawful provision having an
        economic effect as close as possible to the original provision.</span>
</p>
<p class="abc"><span>14.10</span> <span><u>Notices</u></span></p>
<p class="bde"><span> 14.10.1</span> <span>Any notice provided for in this Agreement
        shall be in writing and and shall be sent to the address of the recipient set out below or
        to such other address as the recipient may designate by notice given in writing to all the
        Parties: </span></p>

<p class="bde"><span><b>Notices to the Company</b></span></p>
@php
    if ($sql->Company == '1') {
        $address = 'VNR Seeds Pvt Ltd, Corporate Center, Canal Road Crossing, Ring Road.1, Raipur,CG 492001';
        $email = 'info@vnrseeds.com';
    } elseif ($sql->Company == '3') {
        $address = 'VNR Nursery Pvt Ltd, Corporate Center, Canal Road Crossing, Ring Road.1, Raipur,CG 492001';
        $email = 'info@vnrnursery.in';
    }
@endphp
<table class="table table-borderless">
    <tr>
        <td style="width: 200px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Address</td>
        <td style="width:100px;"></td>
        <td>{{ $address }}</td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-mail</td>
        <td style="width:100px;"></td>
        <td>{{ $email }}</td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;For attention of</td>
        <td style="width:100px;"></td>
        <td>Director / Manager HR</td>
    </tr>
</table>

<p class="bde"><span><b>Notices to the Executive:</b></span></p>

<table class="table table-borderless">
    <tr>
        <td style="width: 200px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Address</td>
        <td style="width:100px;"></td>
        <td>{{ $sql->perm_address }},
            {{ $sql->perm_city }},
            Dist-{{ getDistrictName($sql->perm_dist) }} ({{ getStateCode($sql->perm_state) }})
            -
            {{ $sql->perm_pin }}</td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-mail</td>
        <td style="width:100px;"></td>
        <td>{{ $sql->Email }}</td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;For attention of</td>
        <td style="width:100px;"></td>
        <td>{{ $sql->Title }} {{ $sql->FName }} {{ $sql->MName }}
            {{ $sql->LName }}</td>
    </tr>
</table>

<p class="bde"><span> 14.10.2</span> <span>Any notice or other communication shall be
        deemed to have been given: (a) if hand delivered, at the time of delivery; (b) if sent by
        courier, at 10.00 a.m. on the third working day after it was dispatched; or (c) if sent by
        email, on the date of transmission, if transmitted before 5.00 p.m. (local time at the place
        of destination) on any working day in the place of destination and in any other case on the
        working day following the date of transmission. In proving the giving of a notice or other
        formal communication it shall be sufficient to prove that delivery was made or that the
        envelope containing the communication was properly addressed and provided to the relevant
        courier, or that the e-mail was properly addressed and transmitted, as the case may
        be.</span></p>
<p class="bde"><span> 14.10.3</span> <span>Any Party may, from time to time, change its
        address or representative for receipt of notices provided for in this Agreement by giving to
        all the Party not less than 10 (ten) days' prior written notice thereof.</span></p>
<p class="abc"><span>14.11</span> <span><u>No Third Party Beneficiaries</u></span></p>
<p class="abc"><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span>This Agreement
        and the rights and obligations created under it shall be binding upon and inure solely to
        the benefit of the Parties hereto and their respective successors and permitted assigns, and
        except as otherwise expressly set forth in this Agreement, the Parties do not intend to
        confer upon any other Person any right, remedy, or claim under or by virtue of this
        Agreement. </span></p>

<p class="abc"><span>14.12</span> <span><u>Reservation of Rights </u></span></p>
<p class="abc"><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span>&nbsp;&nbsp;&nbsp;No forbearance,
        indulgence or relaxation or inaction by any Party at any time to require performance of any
        of the provisions of this Agreement shall in any way affect, diminish or prejudice the right
        of such Party to require performance of that provision, and any waiver or acquiescence by
        any Party of any breach of any of the provisions of this Agreement shall not be construed as
        a waiver or acquiescence of any continuing or succeeding breach of such provisions, a waiver
        of any right under or arising out of this Agreement or acquiescence to or recognition of
        rights other than that expressly stipulated in this Agreement.</span></p>
<p class="abc"><span>14.13</span> <span><u>Independent Rights </u></span></p>
<p class="abc"><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span>&nbsp;&nbsp;&nbsp;Each of the
        rights of the Parties hereto under this Agreement are independent, cumulative and without
        prejudice to all other rights available to them, and except as expressly provided in this
        Agreement the exercise or non-exercise of any such rights shall not prejudice or constitute
        a waiver of any other right of the Party, whether under this Agreement or otherwise.</span>
</p>

<p class="abc"><span>14.14</span> <span><u>Counterparts </u></span></p>
<p class="abc"><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span>&nbsp;&nbsp;&nbsp;This Agreement
        may be executed in one or more counterparts, each of which shall be deemed an original but
        all of which together shall constitute one and the same instrument and any Party may execute
        this Agreement by signing any one or more of such originals or counterparts. The delivery of
        signed counterparts by electronic mail in “portable document format” (“.pdf”) shall be as
        effective as signing and delivering the counterpart in person.</span></p>
<pagebreak>
    <p><b>IN WITNESS WHEREOF,</b> the parties hereto have executed this Agreement as of the date first
        set forth above.<br><br>Signed for and on behalf the Company,</p>


    <p style="margin-bottom: 0px;">For & On Behalf of,</p>
    <p style="margin: 0px;">@if($sql->Company ==1)
            VNR Seeds Pvt. Ltd.
        @else
            VNR Nursery Pvt. Ltd.
        @endif</p>

    <div style="text-align: center; font-weight:bold; margin-top:10px; ">
        <div style="float: left; width: 50%; text-align: left;">___________________<br>Authorized Signatory<br></div>


    </div>

    <br>
    <hr>
    <p style="text-align: justify">The Executive represents that he / she has read carefully and fully understands the
        terms of this Agreement. Executive acknowledges that he / she is executing this Agreement voluntarily and
        knowingly and that he / she has not relied on any representations, promises, or agreements of any kind made to
        the Executive in connection with Executive’s decision to accept the terms of this Agreement, other than those
        set forth in this Executive Employment Agreement.
    </p>

    <p><b>Signature of the Executive</b></p>

    <p style="margin-bottom: 2px">_____________________<br><b>{{ $sql->FName }} {{ $sql->MName }}
            {{ $sql->LName }}</b></p>

    <p>Date: _____________</p>
