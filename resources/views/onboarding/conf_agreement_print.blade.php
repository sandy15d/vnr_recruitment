@php


    $JAId = base64_decode($_REQUEST['jaid']);

    $sql = DB::table('jobapply')
        ->leftJoin('appointing', 'appointing.JAId', '=', 'jobapply.JAId')
        ->leftJoin('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')
        ->leftJoin('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
        ->leftJoin('candjoining', 'jobapply.JAId', '=', 'candjoining.JAId')
        ->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')
        ->leftJoin('jf_family_det', 'jobcandidates.JCId', '=', 'jf_family_det.JCId')
        ->select('appointing.*', 'offerletterbasic.*', 'candjoining.JoinOnDt', 'jobcandidates.Title', 'jobcandidates.SpouseName', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.FatherTitle', 'jobcandidates.FatherName', 'jobcandidates.Gender', 'jobcandidates.MaritalStatus', 'jobcandidates.Email', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_dist', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin')
        ->where('jobapply.JAId', $JAId)
        ->first();

@endphp
<style>
    ol li{
        text-align: justify;
    }
    .abc {
        margin-left: 42.55pt;
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

<body>

    <p style="margin-bottom: 30px;"></p>

    <p style="text-align: center;">  <b style="font-weight: bold;"><u>CONFIDENTIALITY AGREEMENT</u></b></p>
    <p style="text-align: justify"> This Confidentiality agreement ("<strong>Agreement</strong>") in
        addition to the Employment/Service Agreement, is made on this


        @if ($sql->ConfLtrDate != '')
            {{ date('dS', strtotime($sql->ConfLtrDate)) }}@else{{ date('dS') }}
        @endif
        day of
        @if ($sql->ConfLtrDate != '')
            {{ date('M', strtotime($sql->ConfLtrDate)) }}
        @else
            {{ date('M') }}
        @endif,
        @if ($sql->ConfLtrDate != '')
            {{ date('Y', strtotime($sql->ConfLtrDate)) }}
        @else
            {{ date('Y') }}
        @endif
    </p>
    <p>By and Between</p>

    <p style="text-align: justify">  <b style="font-weight: bold;"> {{ strtoupper(getcompany_name($sql->Company)) }}</b>, a
        private limited company incorporated under the provisions of the Companies Act, 1956 and
        having its Registered and Administrative office situated at Corporate Centre, Canal Road
        Crossing, Ring Road No.1, Raipur 492006, C.G. (hereinafter referred to as the “Company”,
        which expression shall, unless inconsistent with the meaning or context thereof, be
        deemed to include its successors and assigns) of the First Part; </p>
    <p style="text-align: center">AND</p>
 {{--   @php
        if ($sql->MaritalStatus != '' || $sql->MaritalStatus != null) {
            if ($sql->MaritalStatus == 'Single') {
                if ($sql->Gender == 'M') {
                    $x = 'S/o. ' . $sql->FatherTitle . ' ' . $sql->FatherName;
                } else {
                    $x = 'D/o. ' . $sql->FatherTitle . ' ' . $sql->FatherName;
                }
            } else {
                if ($sql->Gender == 'M') {
                    $x = 'S/o. ' . $sql->FatherTitle . ' ' . $sql->FatherName;
                } else {
                    $x = 'W/o. ' . $sql->FatherTitle . ' ' . $sql->SpouseName;
                }
            }
        } else {
            if ($sql->Gender == 'M') {
                $x = 'S/o. ' . $sql->FatherTitle . ' ' . $sql->FatherName;
            } else {
                $x = 'D/o. ' . $sql->FatherTitle . ' ' . $sql->FatherName;
            }
        }
    @endphp--}}
    @php

        if ($sql->Gender == 'M') {
            $x = 'S/o. ' . $sql->FatherTitle . ' ' . $sql->FatherName;
        } else {
            $x = 'D/o. ' . $sql->FatherTitle . ' ' . $sql->FatherName;
        }

    @endphp
    <p style="text-align: justify"><strong>{{ $sql->Title }} {{ $sql->FName }}
            {{ $sql->MName }}
            {{ $sql->LName }}</strong>, <strong>{{ $x }}</strong> permanently residing at
          <b style="font-weight: bold;">{{ $sql->perm_address }},
            {{ $sql->perm_city }},Dist-{{ getDistrictName($sql->perm_dist) }},{{ getStateName($sql->perm_state) }},
            {{ $sql->perm_pin }},</b> hereinafter referred to as the “Employee” of the Second Part.
        The Company and the Employee shall hereinafter collectively be referred to as “Parties” and
        individually as “Party” as the context may require.
    </p>




        <p style="font-weight: bold;">WHEREAS:</p>

    <ol type="a">
        <li> The company has selected the Employee for the post   <b style="font-weight: bold;">{{ getCandidateFullDesignation($sql->JAId) }}</b> at
              <b style="font-weight: bold;">Grade-{{ getGradeValue($sql->Grade) }}</b>, in the
            Company as per the terms and conditions stipulated in the letter of appointment dated
              <b style="font-weight: bold;">{{ date('d-m-Y', strtotime($sql->JoinOnDt)) }}</b> and the Employee has agreed to abide with the said
            terms and conditions of his
            or her employment.</li>
        <li>The Company and its direct or indirect subsidiaries or affiliates or associate companies
            develop and use valuable technical, non-technical, proprietary information, Intellectual
            Property and confidential information (hereinafter defined), and the thus Company wishes to
            prevent others from using the above;</li>
        <li>The Company may also disclose valuable technical, non-technical, proprietary information,
            Intellectual Property and confidential information to the Employee and since, the Employee
            is entitled to have access to the above information of the Company, the Employee hereby
            undertakes not to disclose or use the said information for any purpose except for the
            purpose for which the same is provided, without obtaining the written consent of the Company
            to disclose or use the same;</li>
        <li>The Employee, during his/her employment and engagement with the Company, may be exposed to,
            or otherwise learn about, valuable technical, non-technical, proprietary information,
            Intellectual Property and confidential information and may come into close contact with many
            confidential matters and information not generally available to the public, which may
            provide the Employee the basis for developing new technology. In consideration of the
            Company agreeing to provide the Employee with the said opportunity, the Employee hereby
            agrees to the provisions of this agreement, with full knowledge that this agreement imposes
            various restrictions;</li>
        <li>The Employee, in the course of his or her employment with the Company may conceive, develop
            new technology or contribute to material or information related to the business of the
            Company. To guard the legitimate interests of the Company, it is necessary for the Company
            to protect the said information either by way of Intellectual Property (hereinafter defined)
            or by holding it secret or confidential, for which the Employee will provide all relevant
            assistance and co-operation;</li>
        <li>That during the employment with the Company and at all times thereafter, the Employee will
            keep and maintain the Confidential Information (defined below), in the strictest secrecy and
            confidence, and further agree not to, directly or indirectly, use or disclose any of the
            Confidential Information for the benefit of himself or otherwise, except as authorized in
            writing by the Company. This restriction shall continue to apply even after the termination
            of this agreement without limit in time.</li>

    </ol>
    <p style="text-align: justify">NOW THEREFORE, in consideration of the mutual covenants and subject
        to the terms and conditions hereinafter set forth, the Parties, intending to be legally bound,
        mutually covenant and agree as follows:</p>

    <b style="font-weight: bold;">1. DEFINITION</b>
    <p class="abc"><span>1.1</span> <span>The following words and phrases shall have the
            following meanings (except where the context otherwise explicitly requires):</span></p>
    <p class="abc"><span>1.2</span> <span>  <b style="font-weight: bold;">“Agreement”</b> shall mean this agreement, all
            attached annexures, schedules and instruments supplemental to or amending, modifying or
            confirming this agreement in accordance with the provisions of this agreement;</span></p>
    <p class="abc"><span>1.3</span> <span>  <b style="font-weight: bold;">“Applicable Laws”</b> shall mean all laws of India,
            ordinances, statutes, rules, notifications, circulars, orders, decrees, injunctions,
            licenses, permits, approvals, authorizations, consents, waivers, privileges, agreements
            and regulations of any governmental authority having jurisdiction over the relevant
            matter as such are in effect as of the date hereof or as may be amended, modified,
            enacted or revoked from time to time hereinafter;</span></p>
    <p class="abc"><span>1.4</span> <span>The   <b style="font-weight: bold;">“Confidential Information”</b> means and
            includes all and/or any information and material which is confidential and proprietary to
            the relevant Party, not generally known or readily ascertainable in the industry, whether in
            tangible or intangible form, or oral or written, electronic, or any other form / medium
            whatsoever, related to the commercial, financial, technical business or affairs of the
            relevant Party and that has or could have commercial value or other utility in the business
            in which the Company is engaged or contemplates engaging, and all information of which the
            unauthorized disclosure could be detrimental to the interest of the Company, whether or not
            such information is defined as Confidential information by the Company. This includes but
            not limited to:</span></p>
    <ul type="none">
        <li>
            <ol type="a">
                <li>Technical information concerning the Company's products and services, including
                    product know-how, inventions, trade secrets, research and development techniques,
                    processes, innovations, patents, patent applications, registered plant varieties,
                    plant variety applications, germ plasm, parent variety materials, species,
                    propagating materials, discoveries, improvements, data, formats, test results,
                    research projects, forecasts, devices, diagrams, software code, electronic codes,
                    test results, processes, research projects and product development, plant &
                    machinery, engineering of the plant, material handling, raw material specifications,
                    consumption norms, rejection parameters, technical memoranda and correspondence;
                </li>
                <li>Information concerning the Company’s business, including cost information, profits,
                    sales, sales information, service, accounting and unpublished financial information,
                    budgets, projections, agreements, pricing policies, business strategies, financial
                    information, personnel information, business plans, markets and marketing methods,
                    product plans, customer lists and customer information, purchasing techniques,
                    supplier lists and supplier information, advertising strategies; marketing plans and
                    strategies; materials concerning the Company’s actual or demonstrably anticipated
                    research and development, and result from any work performed by the Employee, for
                    the Company, results from or are suggested by any work done by the Company or at the
                    Company’s request, or any projects specifically assigned to the Employee; or results
                    from the Employee’s access to any of the Company’s memoranda, notes, records,
                    drawings, sketches, models, maps, customer lists, research results, data, formulae,
                    specifications, inventions, processes, equipment or other materials (collectively
                    called “Company’s material”);</li>
                <li>The Company’s material such as biological material, germplasm, germplasm resources,
                    inbred/ parental lines, breeding material pedigree, hybrid seeds including pre-
                    commercial and commercial hybrid seeds, plant propagation material, laboratory
                    notebooks, field note books, and field trial notebooks or any other proprietary
                    material provided by the Company; Financial Information; Sales Volume, Expenses &
                    Margins; Business Strategies; Operational Methods; Consulting Contracts; Supplier
                    Information; Purchasing Information; Product Development; Strategies; Techniques or
                    Plans; Research and Development; Acquisition Transaction and Personal Plans.</li>
                <li>Information concerning Company’s Employees, including salaries, strengths,
                    weaknesses and skills;</li>
                <li>Information submitted by Company’s customers, suppliers, Employees, consultants or
                    co-venture partners with the Company for study, evaluation or use; </li>
                <li>Planning, Engineering and Technical Information, including, without limitation
                    Formulae; Product Specifications; Product Formulation; Manufacturing Process;
                    Patterns; Methods; Plans; and know-how.</li>
            </ol>
        </li>
    </ul>

    <ul type="none">
        <li>
            <ol type="a" start="7">


                <li>Any information, which the Company from time to time maintains as confidential
                    information or declares to be confidential information, provided the same is not
                    generally available in the public domain.</li>
                <li>Any other information, which is unique to the Company or which, gives the Company an
                    advantage over competitors who do not have such information; and</li>
                <li>Any other information not generally known to the public which, if misused or
                    disclosed, could reasonably be expected to adversely affect the Company’s business.
                </li>
            </ol>
        </li>
    </ul>

    <p class="abc"><span>1.5</span> <span>  <b style="font-weight: bold;">“Effective date”</b> shall mean the date of execution
            of this Agreement;</span></p>
    <p class="abc"><span>1.6</span> <span>  <b style="font-weight: bold;">“Exempted Information"</b> means information that the
            Employee can demonstrate by written records (a) was in his/her possession prior to the time
            of disclosure; (b) is or becomes public knowledge through no fault, omission, or other act
            of the Employee; (c) is obtained from a third party under no obligation of confidentiality
            to the Company or (d) was independently developed by or for the Employee prior to his/her
            appointment by the Company.</span></p>
    <p class="abc"><span>1.7</span> <span>  <b style="font-weight: bold;">“Governmental Authority”</b> shall mean any national,
            federal, state, local, municipal district or other sub-division governmental or
            quasi-governmental authority, statutory authority, government department, agency,
            commission, board, tribunal, arbitral tribunal, arbitrator or court or other law, rule or
            regulation-making entity to the extent that the rules, regulations and standards,
            requirements, procedures or orders of such authority, body or other organization have the
            force of law.</span></p>
    <p class="abc"><span>1.8</span> <span>  <b style="font-weight: bold;">“Intellectual Property”</b> means and includes, in
            relation to the Company and its predecessors in title, all: </span></p>
    <ul type="none">
        <li>
            <ol type="a">
                <li>Registered plant varieties and applications under the Protection of Plant Varieties
                    and Farmers’ Rights Act, 2001 and under plant variety protection legislation in any
                    other jurisdiction other than India;</li>
                <li>Patents and patent applications, utility models whether or not registered, whether
                    or not granted and whether or not such patents or applications are modified,
                    withdrawn or resubmitted; </li>
                <li>Registered and unregistered trade names, brand names, trade dress, trademarks,
                    service names and service marks (and applications for registration of the same) and
                    all goodwill associated therewith including the right to use any licences,
                    trademarks, franchises or any other business or commercial rights; </li>
                <li>Copyrights and copyright registrations (and applications for the same) and works of
                    original authorship (whether or not the copyright has been registered); </li>
                <li>Registered and/or unregistered designs whether or not such registered designs or
                    design applications are modified, withdrawn or resubmitted;</li>
                <li>Trade secrets, know-how, formulae, compilations, devices methods, techniques or
                    processes, and confidential or proprietary information;</li>
                <li>Inventions, discoveries, varieties including but not limited to segregating lines,
                    hybrids, etc; concepts, formula, arts, systems, methods, processes, composition,
                    machines, manufactures, developments, improvements and designs, drawings, sketches,
                    models, samples, prototypes,(whether or not patentable or reduced to practice),
                    including, without limitation, all notes, journals or other compilations of data,
                    know-how, research and development and any related and/or supporting documentation
                    generated in the invention that is conceived, developed, made or reduced to practice
                    or development process; </li>
                <li>Computer software program including but not limited to source code, object code,
                    data and documentation;</li>
                <li>Domain names or uniform resource locators used in connection with any global
                    computer or electronic network (including, without limitation, the Internet and the
                    World Wide Web) together with all translations, adaptations, derivations, and
                    combinations thereof and including all goodwill associated therewith, all
                    applications, registrations, and renewals in connection therewith, and all source
                    code, object code, data and documentation relating thereto; and </li>
                <li>All other Intellectual Property Rights associated with the foregoing. </li>
            </ol>
        </li>
    </ul>

    <p class="abc"><span>1.9</span> <span>  <b style="font-weight: bold;">“Intellectual Property Rights”</b> shall mean and
            include: </span></p>
    <ul type="none">
        <li>
            <ol type="a">
                <li>All rights, title, and interests under any statute or under common law, including
                    patent rights, plant variety rights, copyrights, including but not limited to moral
                    rights, design rights, trademarks rights and/or any similar rights in the
                    Intellectual Property, in all countries except India, whether negotiable or not;
                </li>
                <li>Any assignments, licenses, permissions and grants in connection therewith;</li>
                <li>Applications for any of the foregoing and the right to apply for them in all
                    countries except India in respect to the Intellectual Property, subject to the laws
                    of the said jurisdiction; </li>
                <li>Right to obtain and hold appropriate registrations in the Intellectual Property in
                    all countries except India;</li>
                <li>All extensions and / or renewals thereof; </li>
                <li>Causes of action in the past, present or future, related thereto including the
                    rights to damages and profits, due or accrued, arising out of past, present or
                    future infringements or violations thereof and the right to sue for and recover the
                    same; and </li>
                <li>All similar or equivalent rights or forms of protection which subsist or will
                    subsist now or in the future in all countries except India. </li>
            </ol>
        </li>
    </ul>
    <p class="abc"><span>1.10</span> <span>  <b style="font-weight: bold;">“Plant Propagation Material”</b> means the varieties
            including but not limited to their vines, seeds, pollen, buds, bud-wood, grafting stock,
            tissue culture material and cuttings of the cultivars or any other vegetative propagation
            material such as meristems from dormant buds, stems, and roots made available to the
            Employee for the purposes of this agreement. </span></p>
    <p>  <b style="font-weight: bold;">2. INTERPRETATION</b></p>
    <p class="abc"><span>2.1</span> <span>Unless the context otherwise requires in this
            Agreement:</span></p>
    <ul type="none">
        <li>
            <ol type="a">
                <li>words used in singular shall include the plural and vice-versa;</li>
                <li>words denoting one gender shall denote the other gender also;</li>
                <li>a person includes a legal or natural person or a partnership firm, trust, government
                    or local authority and shall also include the legal representative or successor in
                    interest of such person;</li>
                <li>reference to the words “include” or “including” shall be construed without
                    limitation;</li>
                <li>reference to this Agreement or any other agreement, deed or other instrument or
                    document shall be construed as a reference to this Agreement or such agreement,
                    deed, instrument or document as the same may from time to time be amended, varied,
                    supplemented or novated;</li>
                <li>the headings and titles in this Agreement are indicative only and shall not be
                    deemed to be part thereof or be taken into consideration in the interpretation or
                    construction hereof; and</li>
                <li>The ejusdem generis rule does not apply to this Agreement. Accordingly, specific
                    words indicating a type, class or category of thing shall not restrict the meaning
                    of general words following such specific words, such as general words introduced by
                    the word other or a similar expression. Similarly, general words followed by
                    specific words shall not be restricted in meaning to the type, class or category of
                    thing indicated by such specific words.</li>

            </ol>
        </li>
    </ul>
    <p>  <b style="font-weight: bold;">3. OBLIGATIONS OF THE EMPLOYEE</b></p>
    <p class="abc"><span>3.1</span> <span>The Employee shall be in whole time service /
            employment of the Company and shall continue to conceive, develop new technology, varieties
            or contribute to material or information related to the business of the Company. The
            Employee may create such developments alone or while working with others, and the
            developments may be developed during working hours or after hours and may be reduced to
            practice during his engagement with the Company or thereafter. </span></p>




    <p class="abc"><span>3.2 </span> <span>The Employee acknowledges and agrees that all such
            developments made in clause 3.1 that are within the scope of the Company’s business
            operations or that relate to any of the Company’s Work, Projects, Products, Field of
            Expertise or Businesses, are and shall remain the Company’s exclusive property. The Employee
            further agrees to give the Company prompt notice of any such development. The Employee
            understands and agrees, that in case of any new development, the Employee shall assist the
            Company, at the Company’s expense, to obtain any Intellectual Property Rights available to
            such developments and execute any and all documents necessary to obtain such Intellectual
            Property Rights in the Company’s name and to otherwise give full recognition and effect to
            the Company’s ownership of any such developments. </span></p>
    <p class="abc"><span>3.3 </span> <span>The Employee agrees that this clause 3.2 shall
            survive the termination of the employment of the Employee because co-operation of the
            Employee may be required much later than original filings for Intellectual Property Rights
            in India and outside and also subsequent to the termination of the Employee’s employment.
        </span></p>
    <p class="abc"><span>3.4 </span> <span>The Employee, during his/her employment with the
            Company, shall maintain accurate records in the log books of all the registered varieties,
            proprietary genetic material, breeding procedures followed by the Company, parentages of
            company products, genetic material with the Company, sources of genetic material, procedures
            and systems followed in the Company. </span></p>
    <p class="abc"><span>3.5 </span> <span>The Employee shall not make any statement or shall
            not disclose/ publish any information about any of the matters of the Company before any
            person or media unless authorized to do so by the Company in writing. </span></p>
    <p class="abc"><span>3.6 </span> <span>The Employee shall not engage or associate himself
            directly/indirectly or in any other manner, whatsoever not stated herein, in any other post
            or work, part time or pursue any course of study without prior permission of the Company.
            The Employee should devote his/her entire time, attention and skill to the best of his/her
            ability for the business of the Company and shall not directly or indirectly be connected
            with, concerned with, employed or engaged in any other business or activities whatsoever
            without the prior permission of the Company and shall not accept any emoluments,
            remuneration, consideration, commission or honoraria whatsoever not stated under the terms
            of his/her employment with the Company, from any person/third party. </span></p>
    <p class="abc"><span>3.7 </span> <span>Further, while employed by the Company, the
            Employee agrees to work on a full-time basis exclusively for the Company and agrees that
            he/she shall not, while he/she is employed by the Company, be employed or engaged in any
            capacity, in promoting, undertaking or carrying on any other business that competes with the
            Company or interferes or could reasonably interfere with his/her duties to the Company
            without the prior written permission of the Company. </span></p>
    <p class="abc"><span>3.8 </span> <span>The Employee acknowledges that at any time upon
            the Company’s request and in any event, upon the termination of his/her engagement with the
            Company, the Employee will immediately deliver to the Company all Data, Manual,
            Specifications, Lists, Notes, Memorandum, Writing, Customer or Product Material Whatsoever,
            including all copies or duplicates (collectively referred to as “Documents” and individually
            as a “Document”). Any and all such Documents (including, without limitation, any of the
            Employee’s notes which the Employee prepared or maintained in the course of his/her
            engagement with the Company) are and will be the Company’s property, and these Documents are
            maintained by the Employee or entrusted to the Employee, on a temporary basis during the
            course of his/her engagement with the Company, and solely for the purposes of the Company.
        </span></p>
    <p class="abc"><span>3.9 </span> <span>The Employee agrees that during his/her engagement
            with the Company and for a period of two years after the terminations of said relations, the
            Employee will not, directly or indirectly, solicit, nor transact any business, with any
            customer of or supplier of the Company, nor, during such two-year time period, the Employee
            will otherwise divert or attempt to divert any existing business of the Company to himself
            or to any Company or other entity with which the Employee may be associated. </span></p>
    <p class="abc"><span>3.10 </span> <span>The Employee agrees that, during his/her
            engagement with the Company and for a period of two years after the termination of the said
            engagement, the Employee will not, directly or indirectly, solicit, induce, recruit or cause
            another person, in the employ of the Company, to terminate his or her employment with the
            Company for the purpose of joining, associating with or being employed by or with any
            business activity with which the Employee is associated or which is in competition with the
            Company. </span></p>

    <p>  <b style="font-weight: bold;">4. CONFIDENTIALITY AND NON-DISCLOSURE</b></p>
    <p class="abc"><span>4.1</span> <span>The Employee undertakes not to use the Confidential
            Information of the Company for any purpose whatsoever other than for and in connection with
            the activities and performance of the Employee of his/her obligations as per the terms and
            conditions of his/her employment with the Company. The Employee agrees that he/she shall not
            disclose any Confidential Information related to the Company, except as specifically
            authorized by the Company in writing.</span></p>
    <p class="abc"><span>4.2 </span> <span>The Employee shall not sell, transfer, publish,
            disclose, display or otherwise make available to others any portion of the Confidential
            Information that it may be in possession of without the Company’s prior written
            consent.</span></p>
    <p class="abc"><span>4.3 </span> <span>The Employee shall immediately notify the Company
            upon discovery of any loss or unauthorized disclosure of the Company’s Confidential
            Information and assist the Company in:
        </span></p>
    <ul type="none">
        <li>
            <ol type="a">
                <li>Obtaining appropriate court order preventing or limiting disclosure or use of such
                    Confidential Information; and </li>
                <li>Prosecution of any claim, demand, suit, action or proceeding against the person that
                    is liable for such loss or unauthorized disclosure. </li>
            </ol>
        </li>
    </ul>
    <p class="abc"><span>4.4 </span> <span>The Employee shall not reproduce the Company’s
            Confidential Information except as required under this Agreement and the terms and
            conditions of the Employee’s employment with the Company. Any reproduction of the Company’s
            Confidential Information will remain the property of the Company and will contain all
            confidential or proprietary notices or legends that appear on the original.</span></p>
    <p class="abc"><span>4.5 </span> <span>The Employee will not have any obligations with
            respect to a specific portion of the Company’s Confidential Information if the Employee can
            demonstrate with competent evidence that such portion amounts to Exempted
            Information.</span></p>
    <p class="abc"><span>4.6 </span> <span>The Employee will not have any obligations with
            respect to a specific portion of the Company’s Confidential Information if the Employee can
            demonstrate with competent evidence that such portion was required to be disclosed by any
            court of competent jurisdiction or any Governmental Authority lawfully requesting the same
            provided that the Employee notifies the Company in advance of such disclosure.</span></p>
    <p class="abc"><span>4.7 </span> <span>The Employee may disclose only such portion of the
            Confidential Information of the Company to the extent such disclosure may be legally
            required or is required by a valid order of a Governmental Authority or judicial body having
            jurisdiction, provided the Employee gives the Company reasonable prior written notice of
            such disclosure and makes reasonable efforts to assist the Company in obtaining a protective
            order preventing or limiting disclosure and/or minimize the risk of any Confidential
            Information being subsequently disclosed further.</span></p>
    <p>  <b style="font-weight: bold;">5. INDEMNIFICATION</b></p>
    <p class="abc"><span>5.1 </span> <span>The Employee understands that if the Employee
            violates any provision of this agreement, the Company may suffer serious losses, and that
            the Company shall have the right to various remedies against the Employee. In addition to
            all other applicable rights and remedies the Company may have against the Employee, the
            Company shall have the following specific rights.</span></p>
    <ul type="none">
        <li>
            <ol type="a">
                <li>The right to specific enforcement and/or injunctive relief, and in that regard, the
                    Employee acknowledges that breach of his/her obligations under this agreement may
                    cause irreparable damage to the Company and that monetary compensation may not
                    provide an adequate remedy.</li>
                <li>The right to require the Employee to account for and pay over to the Company all
                    compensation, profits, money, accruals, increments, or other benefits which he/she
                    may derive or receive arising from the breach of his/her obligations to the Company.
                </li>
            </ol>
        </li>
    </ul>
    <p class="abc"><span>5.2 </span> <span>The Employee further agrees to reimburse the
            Company for all its expenses and damages that it may incur as a result of the violation of
            any provision of this agreement by the Employee. This obligation shall include court costs,


            <p>  <b style="font-weight: bold;">6. GENERAL</b></p>
            <p class="abc"><span>6.1 </span> <span>  <b style="font-weight: bold;"><u>Notice:</u></b> Notices and all other communications
                    contemplated by this agreement shall be in writing and shall be deemed to have been duly
                    given when personally delivered or when mailed by certified mail, return receipt requested
                    and postage prepaid. In the case of the Company, mailed notices shall be addressed to its
                    corporate headquarters at the address set forth on the first page of this agreement, and all
                    notices shall be directed to HR. In the case of the Employee, mailed notices shall be
                    addressed to the Employee at the address or email id set forth on the first page of this
                    agreement.</span></p>
            <p class="abc"><span>6.2 </span> <span>  <b style="font-weight: bold;"><u>Waiver:</u></b> No provision of this agreement shall be
                    modified, waived, or discharged unless the modification, waiver or discharge is agreed to in
                    writing and signed by the appropriate parties. No waiver by either party or any breach of,
                    or compliance with, any condition or provision of this agreement by the other party shall be
                    considered a waiver of any other condition or provision or of the same condition or
                    provision at another time.</span></p>
            <p class="abc"><span>6.3 </span> <span>  <b style="font-weight: bold;"><u>Dispute Resolution, Governing Law, and
                            Jurisdiction:</u></b> This Agreement shall be governed by the laws of India and any court of
                    competent jurisdiction in India in connection with any matter pertaining to this agreement
                    shall have exclusive jurisdiction. </span></p>
            <p class="abc"><span>6.4 </span> <span>  <b style="font-weight: bold;"><u>Attorneys’ Fees:</u></b> In any litigation to enforce the
                    terms of this agreement, the prevailing party shall be entitled to collect its costs and
                    fees, including reasonable attorneys’ fees.</span></p>

            <p>  <b style="font-weight: bold;">7. PAYMENT OF STAMP DUTY</b></p>
            <p class="abc"><span>7.1</span> <span>Stamp duty and registration charges on this
                    agreement shall be borne and paid by the Company.</span></p>
            <p>  <b style="font-weight: bold;">8. Amendment</b></p>
            <p style="text-align: justify">This agreement shall not be amended, modified, or supplemented except
                by a written instrument executed by each of the Parties.</p>


            <p>  <b style="font-weight: bold;">9. Counterparts</b></p>
            <p style="text-align: justify">This agreement may be executed in one or more counterparts including
                counterparts transmitted by facsimile, each of which shall be deemed an original, but all of
                which signed and taken together, shall constitute one document. </p>

            <p>  <b style="font-weight: bold;">10. Entire Agreement</b></p>
            <p style="text-align: justify">This agreement along with the Schedules and the documents referred to
                in it, constitutes the whole agreement between the Parties relating to the subject matter hereof
                and supersedes all prior negotiations, representations, undertakings, and agreements on any
                subject matter of this agreement. </p>

            <p>  <b style="font-weight: bold;">11. Amendment</b></p>
            <p style="text-align: justify">If any provision of this agreement is determined to be void or
                unenforceable under the applicable laws, such provision shall be deemed amended or deleted in so
                far as reasonably with the remaining part of this agreement and to the extent necessary to
                conform to applicable law and the remaining part shall remain valid and enforceable as
                applicable at the time of execution of this agreement.</p>


            <p>IN WITNESS WHEREOF, the Parties have set their hands and seal on the day and year above written.
            </p>
            <br><br>
<div style="text-align: center;  margin-top:10px; ">
        <div style="float: left; width: 50%; text-align: left;">For & On Behalf of<br>VNR Seeds Pvt. Ltd.<br> </div>

        <div
            style="float: right; width: 50%; text-align: right;">Signature of the Employee</div>
    </div>
    <div style="text-align: center; font-weight:bold; margin-top:10px; ">
        <div style="float: left; width: 50%; text-align: left;">___________________<br>Authorized Signatory<br> </div>

        <div
            style="float: right; width: 50%; text-align: right;">_________________<br>{{ $sql->FName }} {{ $sql->MName }}
            {{ $sql->LName }}</div>
    </div>

            <br><br>
        <hr>
            <table class="table" style="width: 100%">
                <tr>
                    <td style="width: 50%">
                        <p><b>Witness 1:</b></p>
                    </td>
                    <td>
                        <p><b>Witness 2:</b></p>
                    </td>
                </tr>
                <tr></tr>
                   <tr>
                    <td>
                        <p>Signature: ______________________________</p>
                    </td>
                    <td>
                        <p>Signature: ______________________________</p>
                    </td>
                </tr>
                <tr>
                    <td>&emsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <p>Name: ________________________________</p>
                    </td>
                    <td>
                        <p>Name: ________________________________</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Address: ______________________________</p>
                    </td>
                    <td>
                        <p>Address: ______________________________</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>______________________________________</p>
                    </td>
                    <td>
                         <p>______________________________________</p>
                    </td>
                </tr>
                <tr>
                    <td>
                         <p>______________________________________</p>
                    </td>
                    <td>
                         <p>______________________________________</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Contact No: ____________________________</p>
                    </td>
                    <td>
                        <p>Contact No: ____________________________</p>
                    </td>
                </tr>


            </table>


</span>
</body>

</html>
