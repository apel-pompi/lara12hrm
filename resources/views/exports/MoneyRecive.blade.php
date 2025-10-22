<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Quotation</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif !important;
        }

        body {
            color: #333;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }

        .invoice-box {
            width: 100%;
        }

        /* ==== HEADER ==== */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #fbc02d;
            margin-bottom: 20px;
        }

        .header-left {
            text-align: left;
        }

        .header-right {
            text-align: right;
            vertical-align: top;
        }

        .tagline {
            font-size: 12px;
            color: #777;
            margin-top: 5px;
        }

        .title {
            font-size: 40px;
            font-weight: bold;
            color: #444;
            line-height: 1;
            margin: 0;
        }

        /* ==== INVOICE INFO ==== */
        .invoice-info {
            width: 100%;
            margin-bottom: 20px;
        }

        .invoice-info td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-info h4 {
            margin: 0;
            font-size: 16px;
        }

        /* ==== TABLE ==== */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .invoice-table th {
            background-color: #999999;
            color: #fff;
            padding: 8px;
            text-align: left;
        }

        .invoice-table td {
            border: 1px solid #bbbbbb;
            padding: 8px;
        }

        .invoice-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* ==== TOTALS ==== */
        .totals {
            text-align: right;
            margin-top: 15px;
        }

        .totals p {
            font-size: 12px;
            margin: 2px 0;
            text-align: right;
        }

        .grand-total {
            background-color: #CCC;
            padding: 8px;
            font-weight: bold;
            font-size: 15px;
            color: #000;
            display: inline-block;
        }

        /* ==== FOOTER ==== */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 12px;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #fbc02d;
            padding: 1px 20px;
        }

        .footer-left {
            text-align: justify;
            font-weight: normal;
            font-size: 10px;
        }

        .footer-left span {
            margin: 0 5px;
        }

        .footer-right {
            display: inline-block;
            width: 32%;
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }


        h2 {
            text-transform: capitalize;
            text-align: center;
            font-size: 12px;
        }

        p {
            font-size: 9px;
            text-align: justify;
            line-height: 10.5px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">

        <!-- ===== HEADER ===== -->
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <img src="{{ public_path('storage/company/' . $company->companylogo) }}" width="120"
                        alt="logo"><br>
                    <div class="tagline">Bridging Dreams To Destinations</div>
                </td>
                <td class="header-right">
                    <div class="title">
                        MONEY RECEIPT<br>
                        <span style="font-size: 12px">Office Copy</span>
                    </div>
                </td>
            </tr>
        </table>

        <!-- ===== INVOICE INFO ===== -->
        <table class="invoice-info">
            <tr>
                <td width="60%">
                    <h4>Invoice To:</h4>
                    <strong>Student Name: </strong>{{ $student->fname }} {{ $student->lname }}<br>
                    <strong>Student ID: </strong>{{ $student->student_id }}
                </td>
                <td style="text-align: right;">
                    <strong>Invoice #:</strong> {{ $receipt->insnumber }}<br>
                    <strong>Date:</strong> {{ $receipt->insdate }}<br>
                    <strong>By:</strong> {{ $receipt->user->name }}
                </td>
            </tr>
        </table>

        <!-- ===== TABLE ===== -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th style="width:50px;text-align:center">SL.</th>
                    <th style="text-align:center">Item Description</th>
                    <th style="width:100px; text-align: right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($receipt->mrdetails ?? [] as $key => $item)
                    <tr>
                        <td style="text-align:center">{{ $key + 1 }}</td>
                        <td>{{ $item->fees->name ?? 'N/A' }}</td>
                        <td style="text-align: right">{{ number_format($item->amount ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- ===== TOTALS ===== -->
        <div class="totals">
            <p><strong>Sub Total:</strong> {{ number_format($receipt->totalamt ?? 0, 2) }}</p>
            <p><strong>Discount:</strong> {{ number_format($receipt->disc_amt ?? 0, 2) }}</p>
            <p class="grand-total">Total: {{ number_format($receipt->netamount ?? 0, 2) }}</p>
            <p>in word: {{ $numberTransformer->toWords($receipt->netamount ?? 0) }} only</p>
        </div>
        <p><strong>Payment Info:</strong></p>
        @switch($receipt->payterms)
            @case('Bank')
                <p>
                    Bank Name: {{ $receipt->bankname }}<br>
                    Branch Name: {{ $receipt->bankbranch }}
                </p>
            @break

            @case('Cheque')
                <p>
                    Bank Name: {{ $receipt->bankname }}<br>
                    Cheque No: {{ $receipt->chequeno }}
                </p>
            @break

            @case('Card')
                <p>Transaction No: {{ $receipt->transactionNo }}</p>
            @break

            @default
                <p>Cash Received</p>
        @endswitch

        <!-- ===== FOOTER ===== -->


        <!-- ===== FOOTER ===== -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-right" style="text-align:center;">
                    <div style="border-top:1px solid #000; width:195px;"></div>
                    <div style="font-size:12px;text-align:center">Student Signature</div>
                </div>
                <div class="footer-right" style="text-align:left;">
                </div>
                <div class="footer-right" style="text-align:right;">
                    <div style="border-top:1px solid #000; width:195px;"></div>
                    <div style="font-size:12px;text-align:center">Authorised Signature</div>
                </div>
            </div>

            <div style="font-size:10px; text-align:center; line-height:14px;">
                Glendon Edu, Chandiwala Mansion, Level-3, House-32, Road-11, Block-G, Banani, Dhaka 1213<br>
                +880 9658 111 222 | glendonedu.com
            </div>
        </div>

    </div>



    <div class="page-break"></div>




    <h2>TERMS AND CONDITIONS OF SERVICES FOR ASSISTING STUDENTS IN SECURING ADMISSION TO OVERSEAS INSTITUTIONS AND
        PROVIDING VISA GUIDANCE:</h2>
    <p>1) <strong>GLENDON EDU</strong> is a professional study abroad consultancy firm dedicated to facilitating the
        following services:<br>
        <span style="margin-left: 10px;padding:5px;display:block">
            a) Counseling and guidance on study destinations, programs, and institutions.<br>
            b) Assistance in preparing and submitting university/college applications.<br>
            c) Guidance in arranging supporting documents for admission and visa applications.<br>
            d) Scheduling and preparing for visa interviews (if applicable) at the respective embassy or consulate.<br>
            e) Providing pre-departure briefings and travel guidance.
        </span>

        <strong>GLENDON EDU</strong> is a consultancy service provider and does not guarantee admission, visa approval,
        or
        scholarship success, as these decisions rest solely with the respective institutions and immigration
        authorities.
    </p>
    <p>
        2) <strong>GLENDON EDU</strong> requires minimum of 2-6 months prior to the commencement of the intake applied
        for, in order
        to process the admissions in the institutes of a particular country. This process will commence only after
        the receipt of the total non-refundable processing fees and all the documents.
    </p>
    <p>
        3) Every student has to pay prescribed non-refundable fees at the time of the enrolment for availing the
        services for Admission, Visa Advisory services and post-landing services. Once student enrolls
        himself/herself with <strong>GLENDON EDU</strong> for being assisted to get admission to institutions and paid
        the GLENDON
        EDU prescribed fees, the fees so paid will not be returned/refunded under any circumstances and the student
        has no right to claim refund of part or whole of the fees so paid to <strong>GLENDON EDU</strong> for the
        assistance, even if
        the student later drops out at any stage, nor will any adjustment be allowed towards new services which the
        students may later request the <strong>GLENDON EDU</strong> to render.
    </p>
    <p>
        4) Service fees do not include third-party costs such as application fees, tuition deposits, visa fees,
        health insurance, translation charges, courier costs, or travel expenses, etc.
    </p>
    <p>
        5) All payments must be made via bank transfer, mobile banking, or in cash for official company receipt.
    </p>
    <p>
        6) The authenticity of all the documents, including academic documents, financial statements, recommendation
        letters, etc. is the responsibility of the student.
    </p>
    <p>
        7) <strong>GLENDON EDU</strong>shall not be held liable for any undue postal delays in the transmission of
        documents or
        receipt of information, nor for any changes to the immigration regulations of the destination country for
        which the application is being processed.
    </p>
    <p>
        8) If the program applied at the time of the application processing is not forthcoming, <strong>GLENDON
            EDU</strong> will
        process the application for admission to different program of a different university or for a different
        country if the student desires so.
    </p>
    <p>
        9) <strong>GLENDON EDU</strong> will not be liable for any loss, damage, expense, or delay arising from:
        <span style="margin-left: 10px;padding:5px;display:block">
            • Decisions made by universities, colleges, or visa authorities.<br>
            • Immigration rule changes after the Agreement date.<br>
            • Acts or omissions by the student.</span>

    </p>
    <p>
        10) <strong>GLENDON EDU</strong> will not be responsible if the student’s admission is rejected due to his/her
        unsatisfactory
        credentials.
    </p>
    <p>
        11) Admission is contingent upon the applicant achieving the minimum IELTS, TOEFL, GRE, GMAT, or SAT scores
        stipulated by the respective institution. Failure to meet the prescribed standards shall result in
        disqualification from consideration for admission.
    </p>
    <p>
        12) The student is required to adhere to all deadlines established and communicated by <strong>GLENDON
            EDU</strong>,
        including but not limited to the submission of documents, payment of tuition and other institutional fees,
        and provision of demand drafts for non-refundable application fees or full tuition fees, as stipulated by
        the respective institution. Any delay in fulfilling these requirements may result in corresponding delays in
        the admission and/or visa processing procedures.
    </p>
    <p>
        13) It is the sole responsibility of the student to arrange for the official transmission of IELTS, TOEFL,
        GRE, GMAT, or SAT scores through the respective examining bodies and to obtain confirmation that such scores
        have been received by the relevant departments. <strong>GLENDON EDU</strong> shall not be held liable for any
        delays arising
        from the student’s failure to forward the scores or to secure confirmation of their receipt.
    </p>
    <p>
        14) In case of visa rejection, the fees paid to the institution will be receivable directly in the name of
        the student in their bank account from the institution within a span of 4-6 months after receiving the
        original refusal letter from the student. <strong>GLENDON EDU</strong> will not be responsible to reimburse the
        fees paid to
        the institution by the student.
    </p>
    <p>
        15) Visa fees paid to the Embassy irrespective of the country is non-refundable and <strong>GLENDON EDU</strong>
        cannot
        reimburse it any under circumstances.
    </p>
    <p>
        16) <strong>GLENDON EDU</strong>’s liability is restricted to the fees paid to <strong>GLENDON EDU</strong> only
        and will not be liable for
        any other fees / expenses incurred by the applicant.
    </p>
    <p>
        I am giving an irrevocable right to <strong>GLENDON EDU</strong> for a period of 6 months from the date of
        submission of all
        documents to process my application for any courses in any institution I desire. If during this time, I
        apply to any institution directly, or through any other agency, <strong>GLENDON EDU</strong> will exercise its
        right to
        prevent me from taking such admission in any other institution.
    </p>


    <p>
        I declare that all the information given above is complete and true to my knowledge. I understand that
        <strong>GLENDON EDU</strong> processes applications for all institution whether they represent them or not. I
        also understand
        & certify that in the event of my applying directly or through third parties, I will do so with reference to
        <strong>GLENDON EDU</strong> and will copy all correspondences, electronic or otherwise to <strong>GLENDON
            EDU</strong>. In an event of my not
        doing so, this authority remains valid and the operative document superseding any other "Agents". I further
        certify that <strong>GLENDON EDU</strong> is authorized to represent me on all matters relating to my
        admissions(s) and
        interact with the institutions on my behalf. I authorize the release of all information relating to my
        application.
    </p>
    <p>
        I have read the above terms and conditions laid down by GLENDON EDU and accept the same.
    </p>
   
    <div class="page-break"></div>




    <!-- ===== HEADER ===== -->
    <table class="header-table">
        <tr>
            <td class="header-left">
                <img src="{{ public_path('storage/company/' . $company->companylogo) }}" width="120"
                    alt="logo"><br>
                <div class="tagline">Bridging Dreams To Destinations</div>
            </td>
            <td class="header-right">
                <div class="title">
                    MONEY RECEIPT<br>
                    <span style="font-size: 12px">Student Copy</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- ===== INVOICE INFO ===== -->
    <table class="invoice-info">
        <tr>
            <td width="60%">
                <h4>Invoice To:</h4>
                <strong>Student Name: </strong>{{ $student->fname }} {{ $student->lname }}<br>
                <strong>Student ID: </strong>{{ $student->student_id }}
            </td>
            <td style="text-align: right;">
                <strong>Invoice #:</strong> {{ $receipt->insnumber }}<br>
                <strong>Date:</strong> {{ $receipt->insdate }}<br>
                <strong>By:</strong> {{ $receipt->user->name }}
            </td>
        </tr>
    </table>

    <!-- ===== TABLE ===== -->
    <table class="invoice-table">
        <thead>
            <tr>
                <th style="width:50px;text-align:center">SL.</th>
                <th style="text-align:center">Item Description</th>
                <th style="width:100px; text-align: right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($receipt->mrdetails ?? [] as $key => $item)
                <tr>
                    <td style="text-align:center">{{ intval($key) + 1 }}</td>
                    <td>{{ $item->fees->name ?? 'N/A' }}</td>
                    <td style="text-align: right">{{ number_format($item->amount ?? 0, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ===== TOTALS ===== -->
    <div class="totals">
        <p><strong>Sub Total:</strong> {{ number_format($receipt->totalamt ?? 0, 2) }}</p>
        <p><strong>Discount:</strong> {{ number_format($receipt->disc_amt ?? 0, 2) }}</p>
        <p class="grand-total">Total: {{ number_format($receipt->netamount ?? 0, 2) }}</p>
        <p>in word: {{ $numberTransformer->toWords($receipt->netamount ?? 0) }} only</p>
    </div>
    <p><strong>Payment Info:</strong></p>
    @switch($receipt->payterms)
        @case('Bank')
            <p>
                Bank Name: {{ $receipt->bankname }}<br>
                Branch Name: {{ $receipt->bankbranch }}
            </p>
        @break

        @case('Cheque')
            <p>
                Bank Name: {{ $receipt->bankname }}<br>
                Cheque No: {{ $receipt->chequeno }}
            </p>
        @break

        @case('Card')
            <p>Transaction No: {{ $receipt->transactionNo }}</p>
        @break

        @default
            <p>Cash Received</p>
    @endswitch

    <!-- ===== FOOTER ===== -->


        <div class="footer">
            <div class="footer-content">
                <div class="footer-right" style="text-align:center;">
                    <div style="border-top:1px solid #000; width:195px;"></div>
                    <div style="font-size:12px;text-align:center">Student Signature</div>
                </div>
                <div class="footer-right" style="text-align:left;">
                </div>
                <div class="footer-right" style="text-align:right;">
                    <div style="border-top:1px solid #000; width:195px;"></div>
                    <div style="font-size:12px;text-align:center">Authorised Signature</div>
                </div>
            </div>

            <div style="font-size:10px; text-align:center; line-height:14px;">
                Glendon Edu, Chandiwala Mansion, Level-3, House-32, Road-11, Block-G, Banani, Dhaka 1213<br>
                +880 9658 111 222 | glendonedu.com
            </div>
        </div>
        <div class="page-break"></div>




    <h2>TERMS AND CONDITIONS OF SERVICES FOR ASSISTING STUDENTS IN SECURING ADMISSION TO OVERSEAS INSTITUTIONS AND
        PROVIDING VISA GUIDANCE:</h2>
    <p>1) <strong>GLENDON EDU</strong> is a professional study abroad consultancy firm dedicated to facilitating the
        following services:<br>
        <span style="margin-left: 10px;padding:5px;display:block">
            a) Counseling and guidance on study destinations, programs, and institutions.<br>
            b) Assistance in preparing and submitting university/college applications.<br>
            c) Guidance in arranging supporting documents for admission and visa applications.<br>
            d) Scheduling and preparing for visa interviews (if applicable) at the respective embassy or consulate.<br>
            e) Providing pre-departure briefings and travel guidance.
        </span>

        <strong>GLENDON EDU</strong> is a consultancy service provider and does not guarantee admission, visa approval,
        or
        scholarship success, as these decisions rest solely with the respective institutions and immigration
        authorities.
    </p>
    <p>
        2) <strong>GLENDON EDU</strong> requires minimum of 2-6 months prior to the commencement of the intake applied
        for, in order
        to process the admissions in the institutes of a particular country. This process will commence only after
        the receipt of the total non-refundable processing fees and all the documents.
    </p>
    <p>
        3) Every student has to pay prescribed non-refundable fees at the time of the enrolment for availing the
        services for Admission, Visa Advisory services and post-landing services. Once student enrolls
        himself/herself with <strong>GLENDON EDU</strong> for being assisted to get admission to institutions and paid
        the GLENDON
        EDU prescribed fees, the fees so paid will not be returned/refunded under any circumstances and the student
        has no right to claim refund of part or whole of the fees so paid to <strong>GLENDON EDU</strong> for the
        assistance, even if
        the student later drops out at any stage, nor will any adjustment be allowed towards new services which the
        students may later request the <strong>GLENDON EDU</strong> to render.
    </p>
    <p>
        4) Service fees do not include third-party costs such as application fees, tuition deposits, visa fees,
        health insurance, translation charges, courier costs, or travel expenses, etc.
    </p>
    <p>
        5) All payments must be made via bank transfer, mobile banking, or in cash for official company receipt.
    </p>
    <p>
        6) The authenticity of all the documents, including academic documents, financial statements, recommendation
        letters, etc. is the responsibility of the student.
    </p>
    <p>
        7) <strong>GLENDON EDU</strong>shall not be held liable for any undue postal delays in the transmission of
        documents or
        receipt of information, nor for any changes to the immigration regulations of the destination country for
        which the application is being processed.
    </p>
    <p>
        8) If the program applied at the time of the application processing is not forthcoming, <strong>GLENDON
            EDU</strong> will
        process the application for admission to different program of a different university or for a different
        country if the student desires so.
    </p>
    <p>
        9) <strong>GLENDON EDU</strong> will not be liable for any loss, damage, expense, or delay arising from:
        <span style="margin-left: 10px;padding:5px;display:block">
            • Decisions made by universities, colleges, or visa authorities.<br>
            • Immigration rule changes after the Agreement date.<br>
            • Acts or omissions by the student.</span>

    </p>
    <p>
        10) <strong>GLENDON EDU</strong> will not be responsible if the student’s admission is rejected due to his/her
        unsatisfactory
        credentials.
    </p>
    <p>
        11) Admission is contingent upon the applicant achieving the minimum IELTS, TOEFL, GRE, GMAT, or SAT scores
        stipulated by the respective institution. Failure to meet the prescribed standards shall result in
        disqualification from consideration for admission.
    </p>
    <p>
        12) The student is required to adhere to all deadlines established and communicated by <strong>GLENDON
            EDU</strong>,
        including but not limited to the submission of documents, payment of tuition and other institutional fees,
        and provision of demand drafts for non-refundable application fees or full tuition fees, as stipulated by
        the respective institution. Any delay in fulfilling these requirements may result in corresponding delays in
        the admission and/or visa processing procedures.
    </p>
    <p>
        13) It is the sole responsibility of the student to arrange for the official transmission of IELTS, TOEFL,
        GRE, GMAT, or SAT scores through the respective examining bodies and to obtain confirmation that such scores
        have been received by the relevant departments. <strong>GLENDON EDU</strong> shall not be held liable for any
        delays arising
        from the student’s failure to forward the scores or to secure confirmation of their receipt.
    </p>
    <p>
        14) In case of visa rejection, the fees paid to the institution will be receivable directly in the name of
        the student in their bank account from the institution within a span of 4-6 months after receiving the
        original refusal letter from the student. <strong>GLENDON EDU</strong> will not be responsible to reimburse the
        fees paid to
        the institution by the student.
    </p>
    <p>
        15) Visa fees paid to the Embassy irrespective of the country is non-refundable and <strong>GLENDON EDU</strong>
        cannot
        reimburse it any under circumstances.
    </p>
    <p>
        16) <strong>GLENDON EDU</strong>’s liability is restricted to the fees paid to <strong>GLENDON EDU</strong> only
        and will not be liable for
        any other fees / expenses incurred by the applicant.
    </p>
    <p>
        I am giving an irrevocable right to <strong>GLENDON EDU</strong> for a period of 6 months from the date of
        submission of all
        documents to process my application for any courses in any institution I desire. If during this time, I
        apply to any institution directly, or through any other agency, <strong>GLENDON EDU</strong> will exercise its
        right to
        prevent me from taking such admission in any other institution.
    </p>


    <p>
        I declare that all the information given above is complete and true to my knowledge. I understand that
        <strong>GLENDON EDU</strong> processes applications for all institution whether they represent them or not. I
        also understand
        & certify that in the event of my applying directly or through third parties, I will do so with reference to
        <strong>GLENDON EDU</strong> and will copy all correspondences, electronic or otherwise to <strong>GLENDON
            EDU</strong>. In an event of my not
        doing so, this authority remains valid and the operative document superseding any other "Agents". I further
        certify that <strong>GLENDON EDU</strong> is authorized to represent me on all matters relating to my
        admissions(s) and
        interact with the institutions on my behalf. I authorize the release of all information relating to my
        application.
    </p>
    <p>
        I have read the above terms and conditions laid down by GLENDON EDU and accept the same.
    </p>
    
    </div>
</body>

</html>
