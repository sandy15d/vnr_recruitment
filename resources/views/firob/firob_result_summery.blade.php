@php
$futbl = 'firob_user';
$JCId = request()->query('jcid');
$Rec = DB::table('jobcandidates')->select('FName','MName','LName')->where('JCId',$JCId)->first();
@endphp
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

    <title>FIRO B</title>
    <link rel="icon" type="image/png" href="../images/favicon.png" />
    <style>
        .th-dark {
            background-color: #25556C !important;
            color: #fff;
        }

        .th-light {
            background-color: #f2f2f2;
        }

        .th-yellow {
            background-color: #33CECF;
            color: #fff;
            text-shadow: 1px 1px 12px rgba(40, 40, 40, 0.55);
        }

        .th-four {
            background-color: #d3f5f5;
        }

        .txtv {
            border: hidden;
            width: 50px;
            text-align: center;
            font-size: 16px;
            font-style: italic;
        }

        .txtv2 {
            border: hidden;
            width: 100%;
          
            text-align: left;
            font-size: 18px;
            font-style: normal;
            color: #000099;
        }

        .eiectbl thead th,
        .eiectbl tbody th,
        .eiectbl tbody td {
            font-size: 12px !important;
            padding: 2px 2px !important;
            text-align: center;
            font-weight: 500;
            border: 1px solid #CCE7F4;
            margin: 0px;
        }

        .eiectbl thead th {
            background-color: #275A72;
            color: #fff;
            font-size: 13px;
            font-weight: bold;
            padding: 7px 3px !important;
        }

        .eiectbl tbody td {

            vertical-align: middle;
        }


        .htf {
            text-align: center;
            font-weight: bold;
            height: 22px;
            color: #FFFFFF;
        }

        .tdf {
            text-align: center;
        }


        #printquebutton {
            display: none;
        }

    </style>
    <script type="text/javascript" language="javascript">
        function printpage() {
            document.getElementById('printbutton').style.display = "none";
            document.getElementById('tblrow1').style.display = "none";
            document.getElementById('tblrow2').style.display = "none";
            document.getElementById('tblrow3').style.display = "none";
            document.getElementById('tblrow4').style.display = "none";
            document.getElementById('tblrow5').style.display = "none";
            document.getElementById('tblrow6').style.display = "none";
            document.getElementById('EIv').style.fontSize = "14px";
            document.getElementById('WIv').style.fontSize = "14px";
            document.getElementById('ECv').style.fontSize = "14px";
            document.getElementById('WCv').style.fontSize = "14px";
            document.getElementById('WAv').style.fontSize = "14px";
            window.print();
            document.getElementById('EIv').style.fontSize = "18px";
            document.getElementById('WIv').style.fontSize = "18px";
            document.getElementById('ECv').style.fontSize = "18px";
            document.getElementById('WCv').style.fontSize = "18px";
            document.getElementById('WAv').style.fontSize = "18px";
            document.getElementById('tblrow1').style.display = "table-row";
            document.getElementById('tblrow2').style.display = "table-row";
            document.getElementById('tblrow3').style.display = "table-row";
            document.getElementById('tblrow4').style.display = "table-row";
            document.getElementById('tblrow5').style.display = "table-row";
            document.getElementById('tblrow6').style.display = "table-row";
            document.getElementById('printbutton').style.display = "block";

        }
    </script>
</head>

<body class="p-5">

    <?php
    $test = 'firob_user';
    
    ?>
    <center><b style="font-size:20px;" class="text-secondary">"RESULT"</b></center>
    <center><b style="color:#31C4C4;font-size:20px;"><i><u>Self Assessment</u></i></b></center><br />
    <center>
        <i>

            <table style="width:100%;top:100px;" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="10" align="center" style="font-size:18px;" class="text-secondary">
                        <table style="width:90%;font-style:normal;" border="0" cellpadding="2" id="AshokReplaceID">
                            <tr>
                                <td><b><u>Name</u>:{{$Rec->FName}} {{$Rec->MName}} {{$Rec->LName}}</b>

                                </td>
                                <td> <button id="printbutton" onclick="printpage()"
                                    class="btn btn-sm btn-primary pull-right">Print</button></td>
                            </tr>
                        </table>


                    </td>
                </tr>

                <tr>
                    <td align="center" colspan="5"><b>Final Result</b><br />
                        <table style="width:50%;" class=" table table-bordered">
                            <tr>
                                <td style="width:12%;" class="th-dark"></td>
                                <td style="width:12%;" align="center" class="th-dark"><b>I</b></td>
                                <td style="width:12%;" align="center" class="th-dark"><b>C</b></td>
                                <td style="width:12%;" align="center" class="th-dark"><b>A</b></td>
                            </tr>
                            <tr>
                                <td align="center" class="th-dark"><b>E</b></td>
                                <td align="center"><input type="text" readonly class="txtv" id="EI" /></td>
                                <td align="center"><input type="text" readonly class="txtv" id="EC" /></td>
                                <td align="center"><input type="text" readonly class="txtv" id="EA" /></td>
                            </tr>
                            <tr>
                                <td align="center" class="th-dark"><b>W</b></td>
                                <td align="center"><input type="text" readonly class="txtv" id="WI" /></td>
                                <td align="center"><input type="text" readonly class="txtv" id="WC" /></td>
                                <td align="center"><input type="text" readonly class="txtv" id="WA" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><span id="Spanmsg"></span></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="10" align="center">
                        <table style="width:90%;font-style:normal;" border="0" cellpadding="2">
                            <tr>
                                <td align="center">
                                    <b>I:</b>&nbsp;Inclusion&nbsp;&nbsp;&nbsp;&nbsp;<b>C:</b>&nbsp;Control&nbsp;&nbsp;&nbsp;&nbsp;<b>A:</b>&nbsp;Affection&nbsp;&nbsp;&nbsp;&nbsp;<b>E:</b>&nbsp;Expressive&nbsp;&nbsp;&nbsp;&nbsp;<b>W:</b>&nbsp;Wanted
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><b>EI:</b>&nbsp;Motivation, Team Building skills & Backup Style (Ability to enthuse
                                    & motivate people)&nbsp;<br />
                                    <input type="text" readonly name="EIv" id="EIv" class="txtv2">
                                   
                                </td>
                            </tr>
                            <tr>
                                <td><b>WI:</b>&nbsp;Communication & Listening (Ability to listen &
                                    persuade)&nbsp;<br />
                                    <input type="text" readonly name="WIv" id="WIv" class="txtv2">
                                </td>
                            </tr>
                            <tr>
                                <td><b>EC:</b>&nbsp;Dominant style, Decision – Making profile (Ability to take
                                    responsibility & make decisions)&nbsp;<br />
                                    <input type="text" readonly name="ECv" id="ECv" class="txtv2">
                                   
                            </tr>
                            <tr>
                                <td><b>WC:</b>&nbsp;Relations with Superiors (Acceptance of
                                    Authority))&nbsp;<br />
                                    <input type="text" readonly name="WCv" id="WCv" class="txtv2">
                                </td>
                            </tr>
                            <tr>
                                <td><b>EA/WA:</b>&nbsp;Sense of Belongingness & Emotional Intelligence (A sense of
                                    relatedness, being connected/ committed)&nbsp;<br />
                                        <input type="text" readonly name="EAv" id="EAv" class="txtv2">
                                        <br />
                                    
                                    <input type="text" readonly name="WAv" id="WAv" class="txtv2">
                                    </td>
                            </tr>




                        </table>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr id="tblrow1">
                    <td></td>
                </tr>



                <tr id="tblrow2">

                    <td style="width:30%;color:#000000;" class="htf" align="center">EI<br />

                        @php
                            $res1 = DB::table('firob_qset')
                                ->where('FiroSetN', 'EI')
                                ->first();
                        @endphp

                        <input type="hidden" id="EI_q1a" value="{{ $res1->q1a }}" /><input type="hidden" id="EI_q2a"
                            value="<?php echo $res1->q2a; ?>" /><input type="hidden" id="EI_q3a"
                            value="<?php echo $res1->q3a; ?>" /><input type="hidden" id="EI_q4a"
                            value="<?php echo $res1->q4a; ?>" /><input type="hidden" id="EI_q5a"
                            value="<?php echo $res1->q5a; ?>" /><input type="hidden" id="EI_q6a"
                            value="<?php echo $res1->q6a; ?>" /><input type="hidden" id="EI_q7a"
                            value="<?php echo $res1->q7a; ?>" /><input type="hidden" id="EI_q8a"
                            value="<?php echo $res1->q8a; ?>" /><input type="hidden" id="EI_q9a"
                            value="<?php echo $res1->q9a; ?>" />

                        <table class="eiectbl table table-bordered">

                            <tr class="th-dark">

                                <td align="center">Q</td>

                                <td align="center" style="border-right:hidden;">Op1</td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">Op2</td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">Op3</td>

                                <td align="center" style="border-left:hidden;">Op4</td>

                                <td align="center">Ans</td>
                                <td align="center">Val</td>

                            </tr>

                            <tr>

                                <td align="center" class="th-light"><?php echo $res1->q1; ?></td>

                                @php
                                    $r11 = DB::table('firob')
                                        ->where('FirobId', $res1->q1)
                                        ->first();
                                    $r11u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res1->q1)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r11->ns1 > 0)
                                        {{ $r11->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r11->ns2 > 0)
                                        {{ $r11->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r11->ns3 > 0)
                                        {{ $r11->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r11->ns4 > 0)
                                        {{ $r11->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r11u->FirobUVal != '' and $r11u->FirobUVal != 0 and $r11u->FirobUVal != 9)
                                        {{ $r11u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">
                                    @php
                                        if ($r11u->FirobUVal != 0 && ($r11->ns1 == $r11u->FirobUVal || $r11->ns2 == $r11u->FirobUVal || $r11->ns3 == $r11u->FirobUVal || $r11->ns4 == $r11u->FirobUVal)) {
                                            $t11 = 1;
                                            echo '1';
                                        } else {
                                            $t11 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t11" value="{{ $t11 }}" />
                                </td>

                            </tr>



                            <tr>

                                <td align="center" class="th-light"><?php echo $res1->q2; ?></td>

                                @php
                                    $r12 = DB::table('firob')
                                        ->where('FirobId', $res1->q2)
                                        ->first();
                                    $r12u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res1->q2)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r12->ns1 > 0)
                                        {{ $r12->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r12->ns2 > 0)
                                        {{ $r12->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r12->ns3 > 0)
                                        {{ $r12->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r12->ns4 > 0)
                                        {{ $r12->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r12u->FirobUVal != '' and $r12u->FirobUVal != 0 and $r12u->FirobUVal != 9)
                                        {{ $r12u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">
                                    @php
                                        if ($r12u->FirobUVal != 0 && ($r12->ns1 == $r12u->FirobUVal || $r12->ns2 == $r12u->FirobUVal || $r12->ns3 == $r12u->FirobUVal || $r12->ns4 == $r12u->FirobUVal)) {
                                            $t12 = 1;
                                            echo '1';
                                        } else {
                                            $t12 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t12" value="{{ $t12 }}" />
                                </td>

                            </tr>






                            <tr>

                                <td align="center" class="th-light"><?php echo $res1->q3; ?></td>

                                @php
                                    $r13 = DB::table('firob')
                                        ->where('FirobId', $res1->q3)
                                        ->first();
                                    $r13u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res1->q3)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r13->ns1 > 0)
                                        {{ $r13->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r13->ns2 > 0)
                                        {{ $r13->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r13->ns3 > 0)
                                        {{ $r13->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r13->ns4 > 0)
                                        {{ $r13->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r13u->FirobUVal != '' and $r13u->FirobUVal != 0 and $r13u->FirobUVal != 9)
                                        {{ $r13u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">
                                    @php
                                        if ($r13u->FirobUVal != 0 && ($r13->ns1 == $r13u->FirobUVal || $r13->ns2 == $r13u->FirobUVal || $r13->ns3 == $r13u->FirobUVal || $r13->ns4 == $r13u->FirobUVal)) {
                                            $t13 = 1;
                                            echo '1';
                                        } else {
                                            $t13 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t13" value="{{ $t13 }}" />
                                </td>

                            </tr>

                            <tr>

                                <td align="center" class="th-light"><?php echo $res1->q4; ?></td>

                                @php
                                    $r14 = DB::table('firob')
                                        ->where('FirobId', $res1->q4)
                                        ->first();
                                    $r14u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res1->q4)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r14->ns1 > 0)
                                        {{ $r14->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r14->ns2 > 0)
                                        {{ $r14->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r14->ns3 > 0)
                                        {{ $r14->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r14->ns4 > 0)
                                        {{ $r14->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r14u->FirobUVal != '' and $r14u->FirobUVal != 0 and $r14u->FirobUVal != 9)
                                        {{ $r14u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">
                                    @php
                                        if ($r14u->FirobUVal != 0 && ($r14->ns1 == $r14u->FirobUVal || $r14->ns2 == $r14u->FirobUVal || $r14->ns3 == $r14u->FirobUVal || $r14->ns4 == $r14u->FirobUVal)) {
                                            $t14 = 1;
                                            echo '1';
                                        } else {
                                            $t14 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t14" value="{{ $t14 }}" />
                                </td>

                            </tr>

                            <tr>

                                <td align="center" class="th-light"><?php echo $res1->q5; ?></td>

                                @php
                                    $r15 = DB::table('firob')
                                        ->where('FirobId', $res1->q5)
                                        ->first();
                                    $r15u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res1->q5)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r15->ns1 > 0)
                                        {{ $r15->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r15->ns2 > 0)
                                        {{ $r15->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r15->ns3 > 0)
                                        {{ $r15->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r15->ns4 > 0)
                                        {{ $r15->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r15u->FirobUVal != '' and $r15u->FirobUVal != 0 and $r15u->FirobUVal != 9)
                                        {{ $r15u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">
                                    @php
                                        if ($r15u->FirobUVal != 0 && ($r15->ns1 == $r15u->FirobUVal || $r15->ns2 == $r15u->FirobUVal || $r15->ns3 == $r15u->FirobUVal || $r15->ns4 == $r15u->FirobUVal)) {
                                            $t15 = 1;
                                            echo '1';
                                        } else {
                                            $t15 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t15" value="{{ $t15 }}" />
                                </td>

                            </tr>

                            <tr>

                                <td align="center" class="th-light"><?php echo $res1->q6; ?></td>

                                @php
                                    $r16 = DB::table('firob')
                                        ->where('FirobId', $res1->q6)
                                        ->first();
                                    $r16u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res1->q6)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r16->ns1 > 0)
                                        {{ $r16->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r16->ns2 > 0)
                                        {{ $r16->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r16->ns3 > 0)
                                        {{ $r16->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r16->ns4 > 0)
                                        {{ $r16->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r16u->FirobUVal != '' and $r16u->FirobUVal != 0 and $r16u->FirobUVal != 9)
                                        {{ $r16u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">
                                    @php
                                        if ($r16u->FirobUVal != 0 && ($r16->ns1 == $r16u->FirobUVal || $r16->ns2 == $r16u->FirobUVal || $r16->ns3 == $r16u->FirobUVal || $r16->ns4 == $r16u->FirobUVal)) {
                                            $t16 = 1;
                                            echo '1';
                                        } else {
                                            $t16 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t16" value="{{ $t16 }}" />
                                </td>

                            </tr>

                            <tr>

                                <td align="center" class="th-light"><?php echo $res1->q7; ?></td>

                                @php
                                    $r17 = DB::table('firob')
                                        ->where('FirobId', $res1->q7)
                                        ->first();
                                    $r17u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res1->q7)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r17->ns1 > 0)
                                        {{ $r17->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r17->ns2 > 0)
                                        {{ $r17->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r17->ns3 > 0)
                                        {{ $r17->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r17->ns4 > 0)
                                        {{ $r17->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r17u->FirobUVal != '' and $r17u->FirobUVal != 0 and $r17u->FirobUVal != 9)
                                        {{ $r17u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">
                                    @php
                                        if ($r17u->FirobUVal != 0 && ($r17->ns1 == $r17u->FirobUVal || $r17->ns2 == $r17u->FirobUVal || $r17->ns3 == $r17u->FirobUVal || $r17->ns4 == $r17u->FirobUVal)) {
                                            $t17 = 1;
                                            echo '1';
                                        } else {
                                            $t17 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t17" value="{{ $t17 }}" />
                                </td>



                            </tr>

                            <tr>

                                <td align="center" class="th-light"><?php echo $res1->q8; ?></td>

                                @php
                                    $r18 = DB::table('firob')
                                        ->where('FirobId', $res1->q8)
                                        ->first();
                                    $r18u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res1->q8)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r18->ns1 > 0)
                                        {{ $r18->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r18->ns2 > 0)
                                        {{ $r18->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r18->ns3 > 0)
                                        {{ $r18->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r18->ns4 > 0)
                                        {{ $r18->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r18u->FirobUVal != '' and $r18u->FirobUVal != 0 and $r18u->FirobUVal != 9)
                                        {{ $r18u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">
                                    @php
                                        if ($r18u->FirobUVal != 0 && ($r18->ns1 == $r18u->FirobUVal || $r18->ns2 == $r18u->FirobUVal || $r18->ns3 == $r18u->FirobUVal || $r18->ns4 == $r18u->FirobUVal)) {
                                            $t18 = 1;
                                            echo '1';
                                        } else {
                                            $t18 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t18" value="{{ $t18 }}" />
                                </td>
                            </tr>

                            <tr>

                                <td align="center" class="th-light"><?php echo $res1->q9; ?></td>

                                @php
                                    $r19 = DB::table('firob')
                                        ->where('FirobId', $res1->q9)
                                        ->first();
                                    $r19u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res1->q9)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r19->ns1 > 0)
                                        {{ $r19->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r19->ns2 > 0)
                                        {{ $r19->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r19->ns3 > 0)
                                        {{ $r19->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r19->ns4 > 0)
                                        {{ $r19->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r19u->FirobUVal != '' and $r19u->FirobUVal != 0 and $r19u->FirobUVal != 9)
                                        {{ $r19u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">
                                    @php
                                        if ($r19u->FirobUVal != 0 && ($r19->ns1 == $r19u->FirobUVal || $r19->ns2 == $r19u->FirobUVal || $r19->ns3 == $r19u->FirobUVal || $r19->ns4 == $r19u->FirobUVal)) {
                                            $t19 = 1;
                                            echo '1';
                                        } else {
                                            $t19 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t19" value="{{ $t19 }}" />
                                </td>
                            </tr>

                        </table>

                    </td>

                    <td></td>

                    <td style="width:30%;color:#000000;" class="htf" align="center">EC<br />
                        @php
                            $res2 = DB::table('firob_qset')
                                ->where('FiroSetN', 'EC')
                                ->first();
                        @endphp
                        <input type="hidden" id="EC_q1a" value="<?php echo $res2->q1a; ?>" /><input type="hidden" id="EC_q2a"
                            value="<?php echo $res2->q2a; ?>" /><input type="hidden" id="EC_q3a"
                            value="<?php echo $res2->q3a; ?>" /><input type="hidden" id="EC_q4a"
                            value="<?php echo $res2->q4a; ?>" /><input type="hidden" id="EC_q5a"
                            value="<?php echo $res2->q5a; ?>" /><input type="hidden" id="EC_q6a"
                            value="<?php echo $res2->q6a; ?>" /><input type="hidden" id="EC_q7a"
                            value="<?php echo $res2->q7a; ?>" /><input type="hidden" id="EC_q8a"
                            value="<?php echo $res2->q8a; ?>" /><input type="hidden" id="EC_q9a"
                            value="<?php echo $res2->q9a; ?>" />

                        <table class="eiectbl table table-bordered">

                            <tr class="th-dark">

                                <td align="center">Q</td>

                                <td align="center" style="border-right:hidden;">Op1</td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">Op2</td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">Op3</td>

                                <td align="center" style="border-left:hidden;">Op4</td>

                                <td align="center">Ans</td>
                                <td align="center">Val</td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res2->q1; ?></td>
                                @php
                                    $r21 = DB::table('firob')
                                        ->where('FirobId', $res2->q1)
                                        ->first();
                                    
                                    $r21u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res2->q1)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r21->ns1 > 0)
                                        {{ $r21->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r21->ns2 > 0)
                                        {{ $r21->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r21->ns3 > 0)
                                        {{ $r21->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r21->ns4 > 0)
                                        {{ $r21->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r21u->FirobUVal != '' and $r21u->FirobUVal != 0 and $r21u->FirobUVal != 9)
                                        {{ $r21u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r21u->FirobUVal != 0 && ($r21->ns1 == $r21u->FirobUVal || $r21->ns2 == $r21u->FirobUVal || $r21->ns3 == $r21u->FirobUVal || $r21->ns4 == $r21u->FirobUVal)) {
                                            $t21 = 1;
                                            echo '1';
                                        } else {
                                            $t21 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t21" value="{{ $t21 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res2->q2; ?></td>
                                @php
                                    $r22 = DB::table('firob')
                                        ->where('FirobId', $res2->q2)
                                        ->first();
                                    
                                    $r22u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res2->q2)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r22->ns1 > 0)
                                        {{ $r22->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r22->ns2 > 0)
                                        {{ $r22->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r22->ns3 > 0)
                                        {{ $r22->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r22->ns4 > 0)
                                        {{ $r22->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r22u->FirobUVal != '' and $r22u->FirobUVal != 0 and $r22u->FirobUVal != 9)
                                        {{ $r22u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r22u->FirobUVal != 0 && ($r22->ns1 == $r22u->FirobUVal || $r22->ns2 == $r22u->FirobUVal || $r22->ns3 == $r22u->FirobUVal || $r22->ns4 == $r22u->FirobUVal)) {
                                            $t22 = 1;
                                            echo '1';
                                        } else {
                                            $t22 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t22" value="{{ $t22 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res2->q3; ?></td>
                                @php
                                    $r23 = DB::table('firob')
                                        ->where('FirobId', $res2->q3)
                                        ->first();
                                    
                                    $r23u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res2->q3)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r23->ns1 > 0)
                                        {{ $r23->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r23->ns2 > 0)
                                        {{ $r23->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r23->ns3 > 0)
                                        {{ $r23->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r23->ns4 > 0)
                                        {{ $r23->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r23u->FirobUVal != '' and $r23u->FirobUVal != 0 and $r23u->FirobUVal != 9)
                                        {{ $r23u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r23u->FirobUVal != 0 && ($r23->ns1 == $r23u->FirobUVal || $r23->ns2 == $r23u->FirobUVal || $r23->ns3 == $r23u->FirobUVal || $r23->ns4 == $r23u->FirobUVal)) {
                                            $t23 = 1;
                                            echo '1';
                                        } else {
                                            $t23 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t23" value="{{ $t23 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res2->q4; ?></td>
                                @php
                                    $r24 = DB::table('firob')
                                        ->where('FirobId', $res2->q4)
                                        ->first();
                                    
                                    $r24u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res2->q4)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r24->ns1 > 0)
                                        {{ $r24->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r24->ns2 > 0)
                                        {{ $r24->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r24->ns3 > 0)
                                        {{ $r24->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r24->ns4 > 0)
                                        {{ $r24->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r24u->FirobUVal != '' and $r24u->FirobUVal != 0 and $r24u->FirobUVal != 9)
                                        {{ $r24u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r24u->FirobUVal != 0 && ($r24->ns1 == $r24u->FirobUVal || $r24->ns2 == $r24u->FirobUVal || $r24->ns3 == $r24u->FirobUVal || $r24->ns4 == $r24u->FirobUVal)) {
                                            $t24 = 1;
                                            echo '1';
                                        } else {
                                            $t24 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t24" value="{{ $t24 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res2->q5; ?></td>
                                @php
                                    $r25 = DB::table('firob')
                                        ->where('FirobId', $res2->q5)
                                        ->first();
                                    
                                    $r25u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res2->q5)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r25->ns1 > 0)
                                        {{ $r25->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r25->ns2 > 0)
                                        {{ $r25->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r25->ns3 > 0)
                                        {{ $r25->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r25->ns4 > 0)
                                        {{ $r25->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r25u->FirobUVal != '' and $r25u->FirobUVal != 0 and $r25u->FirobUVal != 9)
                                        {{ $r25u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r25u->FirobUVal != 0 && ($r25->ns1 == $r25u->FirobUVal || $r25->ns2 == $r25u->FirobUVal || $r25->ns3 == $r25u->FirobUVal || $r25->ns4 == $r25u->FirobUVal)) {
                                            $t25 = 1;
                                            echo '1';
                                        } else {
                                            $t25 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t25" value="{{ $t25 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res2->q6; ?></td>
                                @php
                                    $r26 = DB::table('firob')
                                        ->where('FirobId', $res2->q6)
                                        ->first();
                                    
                                    $r26u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res2->q6)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r26->ns1 > 0)
                                        {{ $r26->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r26->ns2 > 0)
                                        {{ $r26->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r26->ns3 > 0)
                                        {{ $r26->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r26->ns4 > 0)
                                        {{ $r26->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r26u->FirobUVal != '' and $r26u->FirobUVal != 0 and $r26u->FirobUVal != 9)
                                        {{ $r26u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r26u->FirobUVal != 0 && ($r26->ns1 == $r26u->FirobUVal || $r26->ns2 == $r26u->FirobUVal || $r26->ns3 == $r26u->FirobUVal || $r26->ns4 == $r26u->FirobUVal)) {
                                            $t26 = 1;
                                            echo '1';
                                        } else {
                                            $t26 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t26" value="{{ $t26 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res2->q7; ?></td>
                                @php
                                    $r27 = DB::table('firob')
                                        ->where('FirobId', $res2->q7)
                                        ->first();
                                    
                                    $r27u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res2->q7)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r27->ns1 > 0)
                                        {{ $r27->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r27->ns2 > 0)
                                        {{ $r27->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r27->ns3 > 0)
                                        {{ $r27->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r27->ns4 > 0)
                                        {{ $r27->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r27u->FirobUVal != '' and $r27u->FirobUVal != 0 and $r27u->FirobUVal != 9)
                                        {{ $r27u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r27u->FirobUVal != 0 && ($r27->ns1 == $r27u->FirobUVal || $r27->ns2 == $r27u->FirobUVal || $r27->ns3 == $r27u->FirobUVal || $r27->ns4 == $r27u->FirobUVal)) {
                                            $t27 = 1;
                                            echo '1';
                                        } else {
                                            $t27 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t27" value="{{ $t27 }}" />
                                </td>
                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res2->q8; ?></td>
                                @php
                                    $r28 = DB::table('firob')
                                        ->where('FirobId', $res2->q8)
                                        ->first();
                                    
                                    $r28u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res2->q8)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r28->ns1 > 0)
                                        {{ $r28->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r28->ns2 > 0)
                                        {{ $r28->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r28->ns3 > 0)
                                        {{ $r28->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r28->ns4 > 0)
                                        {{ $r28->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r28u->FirobUVal != '' and $r28u->FirobUVal != 0 and $r28u->FirobUVal != 9)
                                        {{ $r28u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r28u->FirobUVal != 0 && ($r28->ns1 == $r28u->FirobUVal || $r28->ns2 == $r28u->FirobUVal || $r28->ns3 == $r28u->FirobUVal || $r28->ns4 == $r28u->FirobUVal)) {
                                            $t28 = 1;
                                            echo '1';
                                        } else {
                                            $t28 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t28" value="{{ $t28 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res2->q9; ?></td>
                                @php
                                    $r29 = DB::table('firob')
                                        ->where('FirobId', $res2->q9)
                                        ->first();
                                    
                                    $r29u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res2->q9)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r29->ns1 > 0)
                                        {{ $r29->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r29->ns2 > 0)
                                        {{ $r29->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r29->ns3 > 0)
                                        {{ $r29->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r29->ns4 > 0)
                                        {{ $r29->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r29u->FirobUVal != '' and $r29u->FirobUVal != 0 and $r29u->FirobUVal != 9)
                                        {{ $r29u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r29u->FirobUVal != 0 && ($r29->ns1 == $r29u->FirobUVal || $r29->ns2 == $r29u->FirobUVal || $r29->ns3 == $r29u->FirobUVal || $r29->ns4 == $r29u->FirobUVal)) {
                                            $t29 = 1;
                                            echo '1';
                                        } else {
                                            $t29 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t29" value="{{ $t29 }}" />
                                </td>

                            </tr>

                        </table>

                    </td>

                    <td></td>

                    <td style="width:30%;color:#000000;" class="htf" align="center">EA<br />
                        @php
                            $res3 = DB::table('firob_qset')
                                ->where('FiroSetN', 'EA')
                                ->first();
                        @endphp
                        <input type="hidden" id="EA_q1a" value="<?php echo $res3->q1a; ?>" /><input type="hidden" id="EA_q2a"
                            value="<?php echo $res3->q2a; ?>" /><input type="hidden" id="EA_q3a"
                            value="<?php echo $res3->q3a; ?>" /><input type="hidden" id="EA_q4a"
                            value="<?php echo $res3->q4a; ?>" /><input type="hidden" id="EA_q5a"
                            value="<?php echo $res3->q5a; ?>" /><input type="hidden" id="EA_q6a"
                            value="<?php echo $res3->q6a; ?>" /><input type="hidden" id="EA_q7a"
                            value="<?php echo $res3->q7a; ?>" /><input type="hidden" id="EA_q8a"
                            value="<?php echo $res3->q8a; ?>" /><input type="hidden" id="EA_q9a"
                            value="<?php echo $res3->q9a; ?>" />

                        <table class="eiectbl table table-bordered">

                            <tr class="th-dark">

                                <td align="center">Q</td>

                                <td align="center" style="border-right:hidden;">Op1</td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">Op2</td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">Op3</td>

                                <td align="center" style="border-left:hidden;">Op4</td>

                                <td align="center">Ans</td>
                                <td align="center">Val</td>

                            </tr>


                            <tr>
                                <td align="center" class="th-light"><?php echo $res3->q1; ?></td>
                                @php
                                    $r31 = DB::table('firob')
                                        ->where('FirobId', $res3->q1)
                                        ->first();
                                    
                                    $r31u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res3->q1)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r31->ns1 > 0)
                                        {{ $r31->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r31->ns2 > 0)
                                        {{ $r31->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r31->ns3 > 0)
                                        {{ $r31->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r31->ns4 > 0)
                                        {{ $r31->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r31u->FirobUVal != '' and $r31u->FirobUVal != 0 and $r31u->FirobUVal != 9)
                                        {{ $r31u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r31u->FirobUVal != 0 && ($r31->ns1 == $r31u->FirobUVal || $r31->ns2 == $r31u->FirobUVal || $r31->ns3 == $r31u->FirobUVal || $r31->ns4 == $r31u->FirobUVal)) {
                                            $t31 = 1;
                                            echo '1';
                                        } else {
                                            $t31 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t31" value="{{ $t31 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res3->q2; ?></td>
                                @php
                                    $r32 = DB::table('firob')
                                        ->where('FirobId', $res3->q2)
                                        ->first();
                                    
                                    $r32u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res3->q2)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r32->ns1 > 0)
                                        {{ $r32->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r32->ns2 > 0)
                                        {{ $r32->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r32->ns3 > 0)
                                        {{ $r32->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r32->ns4 > 0)
                                        {{ $r32->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r32u->FirobUVal != '' and $r32u->FirobUVal != 0 and $r32u->FirobUVal != 9)
                                        {{ $r32u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r32u->FirobUVal != 0 && ($r32->ns1 == $r32u->FirobUVal || $r32->ns2 == $r32u->FirobUVal || $r32->ns3 == $r32u->FirobUVal || $r32->ns4 == $r32u->FirobUVal)) {
                                            $t32 = 1;
                                            echo '1';
                                        } else {
                                            $t32 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t32" value="{{ $t32 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res3->q3; ?></td>
                                @php
                                    $r33 = DB::table('firob')
                                        ->where('FirobId', $res3->q3)
                                        ->first();
                                    
                                    $r33u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res3->q3)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r33->ns1 > 0)
                                        {{ $r33->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r33->ns2 > 0)
                                        {{ $r33->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r33->ns3 > 0)
                                        {{ $r33->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r33->ns4 > 0)
                                        {{ $r33->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r33u->FirobUVal != '' and $r33u->FirobUVal != 0 and $r33u->FirobUVal != 9)
                                        {{ $r33u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r33u->FirobUVal != 0 && ($r33->ns1 == $r33u->FirobUVal || $r33->ns2 == $r33u->FirobUVal || $r33->ns3 == $r33u->FirobUVal || $r33->ns4 == $r33u->FirobUVal)) {
                                            $t33 = 1;
                                            echo '1';
                                        } else {
                                            $t33 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t33" value="{{ $t33 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res3->q4; ?></td>
                                @php
                                    $r34 = DB::table('firob')
                                        ->where('FirobId', $res3->q4)
                                        ->first();
                                    
                                    $r34u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res3->q4)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r34->ns1 > 0)
                                        {{ $r34->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r34->ns2 > 0)
                                        {{ $r34->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r34->ns3 > 0)
                                        {{ $r34->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r34->ns4 > 0)
                                        {{ $r34->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r34u->FirobUVal != '' and $r34u->FirobUVal != 0 and $r34u->FirobUVal != 9)
                                        {{ $r34u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r34u->FirobUVal != 0 && ($r34->ns1 == $r34u->FirobUVal || $r34->ns2 == $r34u->FirobUVal || $r34->ns3 == $r34u->FirobUVal || $r34->ns4 == $r34u->FirobUVal)) {
                                            $t34 = 1;
                                            echo '1';
                                        } else {
                                            $t34 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t34" value="{{ $t34 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res3->q5; ?></td>
                                @php
                                    $r35 = DB::table('firob')
                                        ->where('FirobId', $res3->q5)
                                        ->first();
                                    
                                    $r35u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res3->q5)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r35->ns1 > 0)
                                        {{ $r35->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r35->ns2 > 0)
                                        {{ $r35->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r35->ns3 > 0)
                                        {{ $r35->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r35->ns4 > 0)
                                        {{ $r35->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r35u->FirobUVal != '' and $r35u->FirobUVal != 0 and $r35u->FirobUVal != 9)
                                        {{ $r35u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r35u->FirobUVal != 0 && ($r35->ns1 == $r35u->FirobUVal || $r35->ns2 == $r35u->FirobUVal || $r35->ns3 == $r35u->FirobUVal || $r35->ns4 == $r35u->FirobUVal)) {
                                            $t35 = 1;
                                            echo '1';
                                        } else {
                                            $t35 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t35" value="{{ $t35 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res3->q6; ?></td>
                                @php
                                    $r36 = DB::table('firob')
                                        ->where('FirobId', $res3->q6)
                                        ->first();
                                    
                                    $r36u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res3->q6)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r36->ns1 > 0)
                                        {{ $r36->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r36->ns2 > 0)
                                        {{ $r36->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r36->ns3 > 0)
                                        {{ $r36->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r36->ns4 > 0)
                                        {{ $r36->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r36u->FirobUVal != '' and $r36u->FirobUVal != 0 and $r36u->FirobUVal != 9)
                                        {{ $r36u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r36u->FirobUVal != 0 && ($r36->ns1 == $r36u->FirobUVal || $r36->ns2 == $r36u->FirobUVal || $r36->ns3 == $r36u->FirobUVal || $r36->ns4 == $r36u->FirobUVal)) {
                                            $t36 = 1;
                                            echo '1';
                                        } else {
                                            $t36 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t36" value="{{ $t36 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res3->q7; ?></td>
                                @php
                                    $r37 = DB::table('firob')
                                        ->where('FirobId', $res3->q7)
                                        ->first();
                                    
                                    $r37u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res3->q7)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r37->ns1 > 0)
                                        {{ $r37->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r37->ns2 > 0)
                                        {{ $r37->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r37->ns3 > 0)
                                        {{ $r37->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r37->ns4 > 0)
                                        {{ $r37->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r37u->FirobUVal != '' and $r37u->FirobUVal != 0 and $r37u->FirobUVal != 9)
                                        {{ $r37u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r37u->FirobUVal != 0 && ($r37->ns1 == $r37u->FirobUVal || $r37->ns2 == $r37u->FirobUVal || $r37->ns3 == $r37u->FirobUVal || $r37->ns4 == $r37u->FirobUVal)) {
                                            $t37 = 1;
                                            echo '1';
                                        } else {
                                            $t37 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t37" value="{{ $t37 }}" />
                                </td>
                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res3->q8; ?></td>
                                @php
                                    $r38 = DB::table('firob')
                                        ->where('FirobId', $res3->q8)
                                        ->first();
                                    
                                    $r38u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res3->q8)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r38->ns1 > 0)
                                        {{ $r38->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r38->ns2 > 0)
                                        {{ $r38->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r38->ns3 > 0)
                                        {{ $r38->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r38->ns4 > 0)
                                        {{ $r38->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r38u->FirobUVal != '' and $r38u->FirobUVal != 0 and $r38u->FirobUVal != 9)
                                        {{ $r38u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r38u->FirobUVal != 0 && ($r38->ns1 == $r38u->FirobUVal || $r38->ns2 == $r38u->FirobUVal || $r38->ns3 == $r38u->FirobUVal || $r38->ns4 == $r38u->FirobUVal)) {
                                            $t38 = 1;
                                            echo '1';
                                        } else {
                                            $t38 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t38" value="{{ $t38 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res3->q9; ?></td>
                                @php
                                    $r39 = DB::table('firob')
                                        ->where('FirobId', $res3->q9)
                                        ->first();
                                    
                                    $r39u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res3->q9)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r39->ns1 > 0)
                                        {{ $r39->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r39->ns2 > 0)
                                        {{ $r39->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r39->ns3 > 0)
                                        {{ $r39->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r39->ns4 > 0)
                                        {{ $r39->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r39u->FirobUVal != '' and $r39u->FirobUVal != 0 and $r39u->FirobUVal != 9)
                                        {{ $r39u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r39u->FirobUVal != 0 && ($r39->ns1 == $r39u->FirobUVal || $r39->ns2 == $r39u->FirobUVal || $r39->ns3 == $r39u->FirobUVal || $r39->ns4 == $r39u->FirobUVal)) {
                                            $t39 = 1;
                                            echo '1';
                                        } else {
                                            $t39 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t39" value="{{ $t39 }}" />
                                </td>

                            </tr>

                        </table>

                    </td>

                </tr>

                <tr id="tblrow3">
                    <td></td>
                </tr>

                <tr id="tblrow4">

                    <td style="width:30%;color:#000000;" class="htf" align="center">WI<br />
                        @php
                            $res4 = DB::table('firob_qset')
                                ->where('FiroSetN', 'WI')
                                ->first();
                        @endphp

                        <input type="hidden" id="WI_q1a" value="<?php echo $res4->q1a; ?>" /><input type="hidden" id="WI_q2a"
                            value="<?php echo $res4->q2a; ?>" /><input type="hidden" id="WI_q3a"
                            value="<?php echo $res4->q3a; ?>" /><input type="hidden" id="WI_q4a"
                            value="<?php echo $res4->q4a; ?>" /><input type="hidden" id="WI_q5a"
                            value="<?php echo $res4->q5a; ?>" /><input type="hidden" id="WI_q6a"
                            value="<?php echo $res4->q6a; ?>" /><input type="hidden" id="WI_q7a"
                            value="<?php echo $res4->q7a; ?>" /><input type="hidden" id="WI_q8a"
                            value="<?php echo $res4->q8a; ?>" /><input type="hidden" id="WI_q9a"
                            value="<?php echo $res4->q9a; ?>" />

                        <table class="eiectbl table table-bordered">

                            <tr class="th-dark">

                                <td align="center">Q</td>

                                <td align="center" style="border-right:hidden;">Op1</td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">Op2</td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">Op3</td>

                                <td align="center" style="border-left:hidden;">Op4</td>

                                <td align="center">Ans</td>
                                <td align="center">Val</td>

                            </tr>


                            <tr>
                                <td align="center" class="th-light"><?php echo $res4->q1; ?></td>
                                @php
                                    $r41 = DB::table('firob')
                                        ->where('FirobId', $res4->q1)
                                        ->first();
                                    
                                    $r41u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res4->q1)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r41->ns1 > 0)
                                        {{ $r41->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r41->ns2 > 0)
                                        {{ $r41->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r41->ns3 > 0)
                                        {{ $r41->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r41->ns4 > 0)
                                        {{ $r41->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r41u->FirobUVal != '' and $r41u->FirobUVal != 0 and $r41u->FirobUVal != 9)
                                        {{ $r41u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r41u->FirobUVal != 0 && ($r41->ns1 == $r41u->FirobUVal || $r41->ns2 == $r41u->FirobUVal || $r41->ns3 == $r41u->FirobUVal || $r41->ns4 == $r41u->FirobUVal)) {
                                            $t41 = 1;
                                            echo '1';
                                        } else {
                                            $t41 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t41" value="{{ $t41 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res4->q2; ?></td>
                                @php
                                    $r42 = DB::table('firob')
                                        ->where('FirobId', $res4->q2)
                                        ->first();
                                    
                                    $r42u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res4->q2)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r42->ns1 > 0)
                                        {{ $r42->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r42->ns2 > 0)
                                        {{ $r42->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r42->ns3 > 0)
                                        {{ $r42->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r42->ns4 > 0)
                                        {{ $r42->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r42u->FirobUVal != '' and $r42u->FirobUVal != 0 and $r42u->FirobUVal != 9)
                                        {{ $r42u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r42u->FirobUVal != 0 && ($r42->ns1 == $r42u->FirobUVal || $r42->ns2 == $r42u->FirobUVal || $r42->ns3 == $r42u->FirobUVal || $r42->ns4 == $r42u->FirobUVal)) {
                                            $t42 = 1;
                                            echo '1';
                                        } else {
                                            $t42 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t42" value="{{ $t42 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res4->q3; ?></td>
                                @php
                                    $r43 = DB::table('firob')
                                        ->where('FirobId', $res4->q3)
                                        ->first();
                                    
                                    $r43u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res4->q3)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r43->ns1 > 0)
                                        {{ $r43->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r43->ns2 > 0)
                                        {{ $r43->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r43->ns3 > 0)
                                        {{ $r43->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r43->ns4 > 0)
                                        {{ $r43->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r43u->FirobUVal != '' and $r43u->FirobUVal != 0 and $r43u->FirobUVal != 9)
                                        {{ $r43u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r43u->FirobUVal != 0 && ($r43->ns1 == $r43u->FirobUVal || $r43->ns2 == $r43u->FirobUVal || $r43->ns3 == $r43u->FirobUVal || $r43->ns4 == $r43u->FirobUVal)) {
                                            $t43 = 1;
                                            echo '1';
                                        } else {
                                            $t43 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t43" value="{{ $t43 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res4->q4; ?></td>
                                @php
                                    $r44 = DB::table('firob')
                                        ->where('FirobId', $res4->q4)
                                        ->first();
                                    
                                    $r44u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res4->q4)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r44->ns1 > 0)
                                        {{ $r44->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r44->ns2 > 0)
                                        {{ $r44->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r44->ns3 > 0)
                                        {{ $r44->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r44->ns4 > 0)
                                        {{ $r44->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r44u->FirobUVal != '' and $r44u->FirobUVal != 0 and $r44u->FirobUVal != 9)
                                        {{ $r44u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r44u->FirobUVal != 0 && ($r44->ns1 == $r44u->FirobUVal || $r44->ns2 == $r44u->FirobUVal || $r44->ns3 == $r44u->FirobUVal || $r44->ns4 == $r44u->FirobUVal)) {
                                            $t44 = 1;
                                            echo '1';
                                        } else {
                                            $t44 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t44" value="{{ $t44 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res4->q5; ?></td>
                                @php
                                    $r45 = DB::table('firob')
                                        ->where('FirobId', $res4->q5)
                                        ->first();
                                    
                                    $r45u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res4->q5)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r45->ns1 > 0)
                                        {{ $r45->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r45->ns2 > 0)
                                        {{ $r45->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r45->ns3 > 0)
                                        {{ $r45->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r45->ns4 > 0)
                                        {{ $r45->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r45u->FirobUVal != '' and $r45u->FirobUVal != 0 and $r45u->FirobUVal != 9)
                                        {{ $r45u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r45u->FirobUVal != 0 && ($r45->ns1 == $r45u->FirobUVal || $r45->ns2 == $r45u->FirobUVal || $r45->ns3 == $r45u->FirobUVal || $r45->ns4 == $r45u->FirobUVal)) {
                                            $t45 = 1;
                                            echo '1';
                                        } else {
                                            $t45 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t45" value="{{ $t45 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res4->q6; ?></td>
                                @php
                                    $r46 = DB::table('firob')
                                        ->where('FirobId', $res4->q6)
                                        ->first();
                                    
                                    $r46u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res4->q6)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r46->ns1 > 0)
                                        {{ $r46->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r46->ns2 > 0)
                                        {{ $r46->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r46->ns3 > 0)
                                        {{ $r46->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r46->ns4 > 0)
                                        {{ $r46->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r46u->FirobUVal != '' and $r46u->FirobUVal != 0 and $r46u->FirobUVal != 9)
                                        {{ $r46u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r46u->FirobUVal != 0 && ($r46->ns1 == $r46u->FirobUVal || $r46->ns2 == $r46u->FirobUVal || $r46->ns3 == $r46u->FirobUVal || $r46->ns4 == $r46u->FirobUVal)) {
                                            $t46 = 1;
                                            echo '1';
                                        } else {
                                            $t46 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t46" value="{{ $t46 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res4->q7; ?></td>
                                @php
                                    $r47 = DB::table('firob')
                                        ->where('FirobId', $res4->q7)
                                        ->first();
                                    
                                    $r47u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res4->q7)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r47->ns1 > 0)
                                        {{ $r47->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r47->ns2 > 0)
                                        {{ $r47->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r47->ns3 > 0)
                                        {{ $r47->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r47->ns4 > 0)
                                        {{ $r47->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r47u->FirobUVal != '' and $r47u->FirobUVal != 0 and $r47u->FirobUVal != 9)
                                        {{ $r47u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r47u->FirobUVal != 0 && ($r47->ns1 == $r47u->FirobUVal || $r47->ns2 == $r47u->FirobUVal || $r47->ns3 == $r47u->FirobUVal || $r47->ns4 == $r47u->FirobUVal)) {
                                            $t47 = 1;
                                            echo '1';
                                        } else {
                                            $t47 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t47" value="{{ $t47 }}" />
                                </td>
                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res4->q8; ?></td>
                                @php
                                    $r48 = DB::table('firob')
                                        ->where('FirobId', $res4->q8)
                                        ->first();
                                    
                                    $r48u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res4->q8)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r48->ns1 > 0)
                                        {{ $r48->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r48->ns2 > 0)
                                        {{ $r48->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r48->ns3 > 0)
                                        {{ $r48->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r48->ns4 > 0)
                                        {{ $r48->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r48u->FirobUVal != '' and $r48u->FirobUVal != 0 and $r48u->FirobUVal != 9)
                                        {{ $r48u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r48u->FirobUVal != 0 && ($r48->ns1 == $r48u->FirobUVal || $r48->ns2 == $r48u->FirobUVal || $r48->ns3 == $r48u->FirobUVal || $r48->ns4 == $r48u->FirobUVal)) {
                                            $t48 = 1;
                                            echo '1';
                                        } else {
                                            $t48 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t48" value="{{ $t48 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res4->q9; ?></td>
                                @php
                                    $r49 = DB::table('firob')
                                        ->where('FirobId', $res4->q9)
                                        ->first();
                                    
                                    $r49u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res4->q9)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r49->ns1 > 0)
                                        {{ $r49->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r49->ns2 > 0)
                                        {{ $r49->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r49->ns3 > 0)
                                        {{ $r49->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r49->ns4 > 0)
                                        {{ $r49->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r49u->FirobUVal != '' and $r49u->FirobUVal != 0 and $r49u->FirobUVal != 9)
                                        {{ $r49u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r49u->FirobUVal != 0 && ($r49->ns1 == $r49u->FirobUVal || $r49->ns2 == $r49u->FirobUVal || $r49->ns3 == $r49u->FirobUVal || $r49->ns4 == $r49u->FirobUVal)) {
                                            $t49 = 1;
                                            echo '1';
                                        } else {
                                            $t49 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t49" value="{{ $t49 }}" />
                                </td>

                            </tr>
                        </table>

                    </td>

                    <td></td>

                    <td style="width:30%;color:#000000;" class="htf" align="center">WC<br />

                        @php
                            $res5 = DB::table('firob_qset')
                                ->where('FiroSetN', 'WC')
                                ->first();
                        @endphp

                        <input type="hidden" id="WC_q1a" value="<?php echo $res5->q1a; ?>" /><input type="hidden" id="WC_q2a"
                            value="<?php echo $res5->q2a; ?>" /><input type="hidden" id="WC_q3a"
                            value="<?php echo $res5->q3a; ?>" /><input type="hidden" id="WC_q4a"
                            value="<?php echo $res5->q4a; ?>" /><input type="hidden" id="WC_q5a"
                            value="<?php echo $res5->q5a; ?>" /><input type="hidden" id="WC_q6a"
                            value="<?php echo $res5->q6a; ?>" /><input type="hidden" id="WC_q7a"
                            value="<?php echo $res5->q7a; ?>" /><input type="hidden" id="WC_q8a"
                            value="<?php echo $res5->q8a; ?>" /><input type="hidden" id="WC_q9a"
                            value="<?php echo $res5->q9a; ?>" />

                        <table class="eiectbl table table-bordered">

                            <tr class="th-dark">

                                <td align="center">Q</td>

                                <td align="center" style="border-right:hidden;">Op1</td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">Op2</td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">Op3</td>

                                <td align="center" style="border-left:hidden;">Op4</td>

                                <td align="center">Ans</td>
                                <td align="center">Val</td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res5->q1; ?></td>
                                @php
                                    $r51 = DB::table('firob')
                                        ->where('FirobId', $res5->q1)
                                        ->first();
                                    
                                    $r51u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res5->q1)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r51->ns1 > 0)
                                        {{ $r51->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r51->ns2 > 0)
                                        {{ $r51->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r51->ns3 > 0)
                                        {{ $r51->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r51->ns4 > 0)
                                        {{ $r51->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r51u->FirobUVal != '' and $r51u->FirobUVal != 0 and $r51u->FirobUVal != 9)
                                        {{ $r51u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r51u->FirobUVal != 0 && ($r51->ns1 == $r51u->FirobUVal || $r51->ns2 == $r51u->FirobUVal || $r51->ns3 == $r51u->FirobUVal || $r51->ns4 == $r51u->FirobUVal)) {
                                            $t51 = 1;
                                            echo '1';
                                        } else {
                                            $t51 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t51" value="{{ $t51 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res5->q2; ?></td>
                                @php
                                    $r52 = DB::table('firob')
                                        ->where('FirobId', $res5->q2)
                                        ->first();
                                    
                                    $r52u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res5->q2)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r52->ns1 > 0)
                                        {{ $r52->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r52->ns2 > 0)
                                        {{ $r52->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r52->ns3 > 0)
                                        {{ $r52->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r52->ns4 > 0)
                                        {{ $r52->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r52u->FirobUVal != '' and $r52u->FirobUVal != 0 and $r52u->FirobUVal != 9)
                                        {{ $r52u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r52u->FirobUVal != 0 && ($r52->ns1 == $r52u->FirobUVal || $r52->ns2 == $r52u->FirobUVal || $r52->ns3 == $r52u->FirobUVal || $r52->ns4 == $r52u->FirobUVal)) {
                                            $t52 = 1;
                                            echo '1';
                                        } else {
                                            $t52 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t52" value="{{ $t52 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res5->q3; ?></td>
                                @php
                                    $r53 = DB::table('firob')
                                        ->where('FirobId', $res5->q3)
                                        ->first();
                                    
                                    $r53u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res5->q3)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r53->ns1 > 0)
                                        {{ $r53->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r53->ns2 > 0)
                                        {{ $r53->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r53->ns3 > 0)
                                        {{ $r53->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r53->ns4 > 0)
                                        {{ $r53->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r53u->FirobUVal != '' and $r53u->FirobUVal != 0 and $r53u->FirobUVal != 9)
                                        {{ $r53u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r53u->FirobUVal != 0 && ($r53->ns1 == $r53u->FirobUVal || $r53->ns2 == $r53u->FirobUVal || $r53->ns3 == $r53u->FirobUVal || $r53->ns4 == $r53u->FirobUVal)) {
                                            $t53 = 1;
                                            echo '1';
                                        } else {
                                            $t53 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t53" value="{{ $t53 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res5->q4; ?></td>
                                @php
                                    $r54 = DB::table('firob')
                                        ->where('FirobId', $res5->q4)
                                        ->first();
                                    
                                    $r54u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res5->q4)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r54->ns1 > 0)
                                        {{ $r54->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r54->ns2 > 0)
                                        {{ $r54->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r54->ns3 > 0)
                                        {{ $r54->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r54->ns4 > 0)
                                        {{ $r54->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r54u->FirobUVal != '' and $r54u->FirobUVal != 0 and $r54u->FirobUVal != 9)
                                        {{ $r54u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r54u->FirobUVal != 0 && ($r54->ns1 == $r54u->FirobUVal || $r54->ns2 == $r54u->FirobUVal || $r54->ns3 == $r54u->FirobUVal || $r54->ns4 == $r54u->FirobUVal)) {
                                            $t54 = 1;
                                            echo '1';
                                        } else {
                                            $t54 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t54" value="{{ $t54 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res5->q5; ?></td>
                                @php
                                    $r55 = DB::table('firob')
                                        ->where('FirobId', $res5->q5)
                                        ->first();
                                    
                                    $r55u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res5->q5)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r55->ns1 > 0)
                                        {{ $r55->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r55->ns2 > 0)
                                        {{ $r55->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r55->ns3 > 0)
                                        {{ $r55->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r55->ns4 > 0)
                                        {{ $r55->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r55u->FirobUVal != '' and $r55u->FirobUVal != 0 and $r55u->FirobUVal != 9)
                                        {{ $r55u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r55u->FirobUVal != 0 && ($r55->ns1 == $r55u->FirobUVal || $r55->ns2 == $r55u->FirobUVal || $r55->ns3 == $r55u->FirobUVal || $r55->ns4 == $r55u->FirobUVal)) {
                                            $t55 = 1;
                                            echo '1';
                                        } else {
                                            $t55 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t55" value="{{ $t55 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res5->q6; ?></td>
                                @php
                                    $r56 = DB::table('firob')
                                        ->where('FirobId', $res5->q6)
                                        ->first();
                                    
                                    $r56u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res5->q6)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r56->ns1 > 0)
                                        {{ $r56->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r56->ns2 > 0)
                                        {{ $r56->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r56->ns3 > 0)
                                        {{ $r56->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r56->ns4 > 0)
                                        {{ $r56->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r56u->FirobUVal != '' and $r56u->FirobUVal != 0 and $r56u->FirobUVal != 9)
                                        {{ $r56u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r56u->FirobUVal != 0 && ($r56->ns1 == $r56u->FirobUVal || $r56->ns2 == $r56u->FirobUVal || $r56->ns3 == $r56u->FirobUVal || $r56->ns4 == $r56u->FirobUVal)) {
                                            $t56 = 1;
                                            echo '1';
                                        } else {
                                            $t56 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t56" value="{{ $t56 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res5->q7; ?></td>
                                @php
                                    $r57 = DB::table('firob')
                                        ->where('FirobId', $res5->q7)
                                        ->first();
                                    
                                    $r57u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res5->q7)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r57->ns1 > 0)
                                        {{ $r57->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r57->ns2 > 0)
                                        {{ $r57->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r57->ns3 > 0)
                                        {{ $r57->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r57->ns4 > 0)
                                        {{ $r57->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r57u->FirobUVal != '' and $r57u->FirobUVal != 0 and $r57u->FirobUVal != 9)
                                        {{ $r57u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r57u->FirobUVal != 0 && ($r57->ns1 == $r57u->FirobUVal || $r57->ns2 == $r57u->FirobUVal || $r57->ns3 == $r57u->FirobUVal || $r57->ns4 == $r57u->FirobUVal)) {
                                            $t57 = 1;
                                            echo '1';
                                        } else {
                                            $t57 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t57" value="{{ $t57 }}" />
                                </td>
                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res5->q8; ?></td>
                                @php
                                    $r58 = DB::table('firob')
                                        ->where('FirobId', $res5->q8)
                                        ->first();
                                    
                                    $r58u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res5->q8)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r58->ns1 > 0)
                                        {{ $r58->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r58->ns2 > 0)
                                        {{ $r58->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r58->ns3 > 0)
                                        {{ $r58->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r58->ns4 > 0)
                                        {{ $r58->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r58u->FirobUVal != '' and $r58u->FirobUVal != 0 and $r58u->FirobUVal != 9)
                                        {{ $r58u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r58u->FirobUVal != 0 && ($r58->ns1 == $r58u->FirobUVal || $r58->ns2 == $r58u->FirobUVal || $r58->ns3 == $r58u->FirobUVal || $r58->ns4 == $r58u->FirobUVal)) {
                                            $t58 = 1;
                                            echo '1';
                                        } else {
                                            $t58 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t58" value="{{ $t58 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res5->q9; ?></td>
                                @php
                                    $r59 = DB::table('firob')
                                        ->where('FirobId', $res5->q9)
                                        ->first();
                                    
                                    $r59u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res5->q9)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r59->ns1 > 0)
                                        {{ $r59->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r59->ns2 > 0)
                                        {{ $r59->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r59->ns3 > 0)
                                        {{ $r59->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r59->ns4 > 0)
                                        {{ $r59->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r59u->FirobUVal != '' and $r59u->FirobUVal != 0 and $r59u->FirobUVal != 9)
                                        {{ $r59u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r59u->FirobUVal != 0 && ($r59->ns1 == $r59u->FirobUVal || $r59->ns2 == $r59u->FirobUVal || $r59->ns3 == $r59u->FirobUVal || $r59->ns4 == $r59u->FirobUVal)) {
                                            $t59 = 1;
                                            echo '1';
                                        } else {
                                            $t59 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t59" value="{{ $t59 }}" />
                                </td>

                            </tr>

                        </table>

                    </td>

                    <td></td>

                    <td style="width:30%;color:#000000;" class="htf" align="center">WA<br />

                        @php
                            $res6 = DB::table('firob_qset')
                                ->where('FiroSetN', 'WA')
                                ->first();
                        @endphp
                        <input type="hidden" id="WA_q1a" value="<?php echo $res6->q1a; ?>" /><input type="hidden" id="WA_q2a"
                            value="<?php echo $res6->q2a; ?>" /><input type="hidden" id="WA_q3a"
                            value="<?php echo $res6->q3a; ?>" /><input type="hidden" id="WA_q4a"
                            value="<?php echo $res6->q4a; ?>" /><input type="hidden" id="WA_q5a"
                            value="<?php echo $res6->q5a; ?>" /><input type="hidden" id="WA_q6a"
                            value="<?php echo $res6->q6a; ?>" /><input type="hidden" id="WA_q7a"
                            value="<?php echo $res6->q7a; ?>" /><input type="hidden" id="WA_q8a"
                            value="<?php echo $res6->q8a; ?>" /><input type="hidden" id="WA_q9a"
                            value="<?php echo $res6->q9a; ?>" />

                        <table class="eiectbl table table-bordered">

                            <tr class="th-dark">

                                <td align="center">Q</td>

                                <td align="center" style="border-right:hidden;">Op1</td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">Op2</td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">Op3</td>

                                <td align="center" style="border-left:hidden;">Op4</td>

                                <td align="center">Ans</td>
                                <td align="center">Val</td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res6->q1; ?></td>
                                @php
                                    $r61 = DB::table('firob')
                                        ->where('FirobId', $res6->q1)
                                        ->first();
                                    
                                    $r61u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res6->q1)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r61->ns1 > 0)
                                        {{ $r61->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r61->ns2 > 0)
                                        {{ $r61->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r61->ns3 > 0)
                                        {{ $r61->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r61->ns4 > 0)
                                        {{ $r61->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r61u->FirobUVal != '' and $r61u->FirobUVal != 0 and $r61u->FirobUVal != 9)
                                        {{ $r61u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r61u->FirobUVal != 0 && ($r61->ns1 == $r61u->FirobUVal || $r61->ns2 == $r61u->FirobUVal || $r61->ns3 == $r61u->FirobUVal || $r61->ns4 == $r61u->FirobUVal)) {
                                            $t61 = 1;
                                            echo '1';
                                        } else {
                                            $t61 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t61" value="{{ $t61 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res6->q2; ?></td>
                                @php
                                    $r62 = DB::table('firob')
                                        ->where('FirobId', $res6->q2)
                                        ->first();
                                    
                                    $r62u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res6->q2)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r62->ns1 > 0)
                                        {{ $r62->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r62->ns2 > 0)
                                        {{ $r62->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r62->ns3 > 0)
                                        {{ $r62->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r62->ns4 > 0)
                                        {{ $r62->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r62u->FirobUVal != '' and $r62u->FirobUVal != 0 and $r62u->FirobUVal != 9)
                                        {{ $r62u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r62u->FirobUVal != 0 && ($r62->ns1 == $r62u->FirobUVal || $r62->ns2 == $r62u->FirobUVal || $r62->ns3 == $r62u->FirobUVal || $r62->ns4 == $r62u->FirobUVal)) {
                                            $t62 = 1;
                                            echo '1';
                                        } else {
                                            $t62 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t62" value="{{ $t62 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res6->q3; ?></td>
                                @php
                                    $r63 = DB::table('firob')
                                        ->where('FirobId', $res6->q3)
                                        ->first();
                                    
                                    $r63u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res6->q3)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r63->ns1 > 0)
                                        {{ $r63->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r63->ns2 > 0)
                                        {{ $r63->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r63->ns3 > 0)
                                        {{ $r63->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r63->ns4 > 0)
                                        {{ $r63->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r63u->FirobUVal != '' and $r63u->FirobUVal != 0 and $r63u->FirobUVal != 9)
                                        {{ $r63u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r63u->FirobUVal != 0 && ($r63->ns1 == $r63u->FirobUVal || $r63->ns2 == $r63u->FirobUVal || $r63->ns3 == $r63u->FirobUVal || $r63->ns4 == $r63u->FirobUVal)) {
                                            $t63 = 1;
                                            echo '1';
                                        } else {
                                            $t63 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t63" value="{{ $t63 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res6->q4; ?></td>
                                @php
                                    $r64 = DB::table('firob')
                                        ->where('FirobId', $res6->q4)
                                        ->first();
                                    
                                    $r64u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res6->q4)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r64->ns1 > 0)
                                        {{ $r64->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r64->ns2 > 0)
                                        {{ $r64->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r64->ns3 > 0)
                                        {{ $r64->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r64->ns4 > 0)
                                        {{ $r64->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r64u->FirobUVal != '' and $r64u->FirobUVal != 0 and $r64u->FirobUVal != 9)
                                        {{ $r64u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r64u->FirobUVal != 0 && ($r64->ns1 == $r64u->FirobUVal || $r64->ns2 == $r64u->FirobUVal || $r64->ns3 == $r64u->FirobUVal || $r64->ns4 == $r64u->FirobUVal)) {
                                            $t64 = 1;
                                            echo '1';
                                        } else {
                                            $t64 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t64" value="{{ $t64 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res6->q5; ?></td>
                                @php
                                    $r65 = DB::table('firob')
                                        ->where('FirobId', $res6->q5)
                                        ->first();
                                    
                                    $r65u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res6->q5)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r65->ns1 > 0)
                                        {{ $r65->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r65->ns2 > 0)
                                        {{ $r65->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r65->ns3 > 0)
                                        {{ $r65->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r65->ns4 > 0)
                                        {{ $r65->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r65u->FirobUVal != '' and $r65u->FirobUVal != 0 and $r65u->FirobUVal != 9)
                                        {{ $r65u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r65u->FirobUVal != 0 && ($r65->ns1 == $r65u->FirobUVal || $r65->ns2 == $r65u->FirobUVal || $r65->ns3 == $r65u->FirobUVal || $r65->ns4 == $r65u->FirobUVal)) {
                                            $t65 = 1;
                                            echo '1';
                                        } else {
                                            $t65 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t65" value="{{ $t65 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res6->q6; ?></td>
                                @php
                                    $r66 = DB::table('firob')
                                        ->where('FirobId', $res6->q6)
                                        ->first();
                                    
                                    $r66u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res6->q6)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r66->ns1 > 0)
                                        {{ $r66->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r66->ns2 > 0)
                                        {{ $r66->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r66->ns3 > 0)
                                        {{ $r66->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r66->ns4 > 0)
                                        {{ $r66->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r66u->FirobUVal != '' and $r66u->FirobUVal != 0 and $r66u->FirobUVal != 9)
                                        {{ $r66u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r66u->FirobUVal != 0 && ($r66->ns1 == $r66u->FirobUVal || $r66->ns2 == $r66u->FirobUVal || $r66->ns3 == $r66u->FirobUVal || $r66->ns4 == $r66u->FirobUVal)) {
                                            $t66 = 1;
                                            echo '1';
                                        } else {
                                            $t66 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t66" value="{{ $t66 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res6->q7; ?></td>
                                @php
                                    $r67 = DB::table('firob')
                                        ->where('FirobId', $res6->q7)
                                        ->first();
                                    
                                    $r67u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res6->q7)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r67->ns1 > 0)
                                        {{ $r67->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r67->ns2 > 0)
                                        {{ $r67->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r67->ns3 > 0)
                                        {{ $r67->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r67->ns4 > 0)
                                        {{ $r67->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r67u->FirobUVal != '' and $r67u->FirobUVal != 0 and $r67u->FirobUVal != 9)
                                        {{ $r67u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r67u->FirobUVal != 0 && ($r67->ns1 == $r67u->FirobUVal || $r67->ns2 == $r67u->FirobUVal || $r67->ns3 == $r67u->FirobUVal || $r67->ns4 == $r67u->FirobUVal)) {
                                            $t67 = 1;
                                            echo '1';
                                        } else {
                                            $t67 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t67" value="{{ $t67 }}" />
                                </td>
                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res6->q8; ?></td>
                                @php
                                    $r68 = DB::table('firob')
                                        ->where('FirobId', $res6->q8)
                                        ->first();
                                    
                                    $r68u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res6->q8)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r68->ns1 > 0)
                                        {{ $r68->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r68->ns2 > 0)
                                        {{ $r68->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r68->ns3 > 0)
                                        {{ $r68->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r68->ns4 > 0)
                                        {{ $r68->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r68u->FirobUVal != '' and $r68u->FirobUVal != 0 and $r68u->FirobUVal != 9)
                                        {{ $r68u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r68u->FirobUVal != 0 && ($r68->ns1 == $r68u->FirobUVal || $r68->ns2 == $r68u->FirobUVal || $r68->ns3 == $r68u->FirobUVal || $r68->ns4 == $r68u->FirobUVal)) {
                                            $t68 = 1;
                                            echo '1';
                                        } else {
                                            $t68 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t68" value="{{ $t68 }}" />
                                </td>

                            </tr>

                            <tr>
                                <td align="center" class="th-light"><?php echo $res6->q9; ?></td>
                                @php
                                    $r69 = DB::table('firob')
                                        ->where('FirobId', $res6->q9)
                                        ->first();
                                    
                                    $r69u = DB::table($futbl)
                                        ->select('FirobUVal')
                                        ->where('userid', $JCId)
                                        ->where('FirobId', $res6->q9)
                                        ->where('SubSts', 'Y')
                                        ->first();
                                @endphp

                                <td align="center" style="border-right:hidden;">
                                    @if ($r69->ns1 > 0)
                                        {{ $r69->ns1 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r69->ns2 > 0)
                                        {{ $r69->ns2 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;border-right:hidden;">
                                    @if ($r69->ns3 > 0)
                                        {{ $r69->ns3 }}
                                    @endif
                                </td>

                                <td align="center" style="border-left:hidden;">
                                    @if ($r69->ns4 > 0)
                                        {{ $r69->ns4 }}
                                    @endif
                                </td>

                                <td align="center" class="th-yellow">
                                    @if ($r69u->FirobUVal != '' and $r69u->FirobUVal != 0 and $r69u->FirobUVal != 9)
                                        {{ $r69u->FirobUVal }}
                                    @endif
                                </td>

                                <td align="center" class="th-four">

                                    @php
                                        if ($r69u->FirobUVal != 0 && ($r69->ns1 == $r69u->FirobUVal || $r69->ns2 == $r69u->FirobUVal || $r69->ns3 == $r69u->FirobUVal || $r69->ns4 == $r69u->FirobUVal)) {
                                            $t69 = 1;
                                            echo '1';
                                        } else {
                                            $t69 = 0;
                                            echo '';
                                        }
                                    @endphp
                                    <input type="hidden" style="width:50px;" id="t69" value="{{ $t69 }}" />
                                </td>

                            </tr>

                        </table>

                    </td>

                </tr>

                <tr id="tblrow5">
                    <td></td>
                </tr>

                <tr id="tblrow6">
                    <td style="height:50px;"></td>
                </tr>


                <script type="text/javascript" language="javascript">
                    var t11 = parseFloat(document.getElementById("t11").value);
                    var t12 = parseFloat(document.getElementById("t12").value);
                    var t13 = parseFloat(document.getElementById("t13").value);
                    var t14 = parseFloat(document.getElementById("t14").value);
                    var t15 = parseFloat(document.getElementById("t15").value);
                    var t16 = parseFloat(document.getElementById("t16").value);
                    var t17 = parseFloat(document.getElementById("t17").value);
                    var t18 = parseFloat(document.getElementById("t18").value);
                    var t19 = parseFloat(document.getElementById("t19").value);
                    var EI = document.getElementById("EI").value = Math.round((t11 + t12 + t13 + t14 + t15 + t16 + t17 + t18 + t19) *
                        100) / 100;
                    if (EI == 0 || EI == 1 || EI == 2 || EI == 3) {
                        document.getElementById("EIv").value = 'Low';
                    } else if (EI == 4 || EI == 5 || EI == 6) {
                        document.getElementById("EIv").value = 'Average';
                    } else if (EI == 7 || EI == 8 || EI == 9) {
                        document.getElementById("EIv").value = 'High';
                    }

                    var t21 = parseFloat(document.getElementById("t21").value);
                    var t22 = parseFloat(document.getElementById("t22").value);
                    var t23 = parseFloat(document.getElementById("t23").value);
                    var t24 = parseFloat(document.getElementById("t24").value);
                    var t25 = parseFloat(document.getElementById("t25").value);
                    var t26 = parseFloat(document.getElementById("t26").value);
                    var t27 = parseFloat(document.getElementById("t27").value);
                    var t28 = parseFloat(document.getElementById("t28").value);
                    var t29 = parseFloat(document.getElementById("t29").value);
                    var EC = document.getElementById("EC").value = Math.round((t21 + t22 + t23 + t24 + t25 + t26 + t27 + t28 + t29) *
                        100) / 100;
                    if (EC == 0 || EC == 1 || EC == 2 || EC == 3) {
                        document.getElementById("ECv").value = 'Low';
                    } else if (EC == 4 || EC == 5 || EC == 6) {
                        document.getElementById("ECv").value = 'Average';
                    } else if (EC == 7 || EC == 8 || EC == 9) {
                        document.getElementById("ECv").value = 'High';
                    }

                    var t31 = parseFloat(document.getElementById("t31").value);
                    var t32 = parseFloat(document.getElementById("t32").value);
                    var t33 = parseFloat(document.getElementById("t33").value);
                    var t34 = parseFloat(document.getElementById("t34").value);
                    var t35 = parseFloat(document.getElementById("t35").value);
                    var t36 = parseFloat(document.getElementById("t36").value);
                    var t37 = parseFloat(document.getElementById("t37").value);
                    var t38 = parseFloat(document.getElementById("t38").value);
                    var t39 = parseFloat(document.getElementById("t39").value);
                    var EA = document.getElementById("EA").value = Math.round((t31 + t32 + t33 + t34 + t35 + t36 + t37 + t38 + t39) *
                        100) / 100;
                    if (EA == 0 || EA == 1 || EA == 2 || EA == 3) {
                        document.getElementById("EAv").value = 'Low';
                    } else if (EA == 4 || EA == 5 || EA == 6) {
                        document.getElementById("EAv").value = 'Average';
                    } else if (EA == 7 || EA == 8 || EA == 9) {
                        document.getElementById("EAv").value = 'High';
                    }

                    var t41 = parseFloat(document.getElementById("t41").value);
                    var t42 = parseFloat(document.getElementById("t42").value);
                    var t43 = parseFloat(document.getElementById("t43").value);
                    var t44 = parseFloat(document.getElementById("t44").value);
                    var t45 = parseFloat(document.getElementById("t45").value);
                    var t46 = parseFloat(document.getElementById("t46").value);
                    var t47 = parseFloat(document.getElementById("t47").value);
                    var t48 = parseFloat(document.getElementById("t48").value);
                    var t49 = parseFloat(document.getElementById("t49").value);
                    var WI = document.getElementById("WI").value = Math.round((t41 + t42 + t43 + t44 + t45 + t46 + t47 + t48 + t49) *
                        100) / 100;
                    if (WI == 0 || WI == 1 || WI == 2 || WI == 3) {
                        document.getElementById("WIv").value = 'Low';
                    } else if (WI == 4 || WI == 5 || WI == 6) {
                        document.getElementById("WIv").value = 'Average';
                    } else if (WI == 7 || WI == 8 || WI == 9) {
                        document.getElementById("WIv").value = 'High';
                    }

                    var t51 = parseFloat(document.getElementById("t51").value);
                    var t52 = parseFloat(document.getElementById("t52").value);
                    var t53 = parseFloat(document.getElementById("t53").value);
                    var t54 = parseFloat(document.getElementById("t54").value);
                    var t55 = parseFloat(document.getElementById("t55").value);
                    var t56 = parseFloat(document.getElementById("t56").value);
                    var t57 = parseFloat(document.getElementById("t57").value);
                    var t58 = parseFloat(document.getElementById("t58").value);
                    var t59 = parseFloat(document.getElementById("t59").value);
                    var WC = document.getElementById("WC").value = Math.round((t51 + t52 + t53 + t54 + t55 + t56 + t57 + t58 + t59) *
                        100) / 100;
                    if (WC == 0 || WC == 1 || WC == 2 || WC == 3) {
                        document.getElementById("WCv").value = 'Low';
                    } else if (WC == 4 || WC == 5 || WC == 6) {
                        document.getElementById("WCv").value = 'Average';
                    } else if (WC == 7 || WC == 8 || WC == 9) {
                        document.getElementById("WCv").value = 'High';
                    }

                    var t61 = parseFloat(document.getElementById("t61").value);
                    var t62 = parseFloat(document.getElementById("t62").value);
                    var t63 = parseFloat(document.getElementById("t63").value);
                    var t64 = parseFloat(document.getElementById("t64").value);
                    var t65 = parseFloat(document.getElementById("t65").value);
                    var t66 = parseFloat(document.getElementById("t66").value);
                    var t67 = parseFloat(document.getElementById("t67").value);
                    var t68 = parseFloat(document.getElementById("t68").value);
                    var t69 = parseFloat(document.getElementById("t69").value);
                    var WA = document.getElementById("WA").value = Math.round((t61 + t62 + t63 + t64 + t65 + t66 + t67 + t68 + t69) *
                        100) / 100;
                    if (WC == 0 || WC == 1 || WC == 2 || WC == 3) {
                        document.getElementById("WCv").value = 'Low';
                    } else if (WC == 4 || WC == 5 || WC == 6) {
                        document.getElementById("WCv").value = 'Average';
                    } else if (WC == 7 || WC == 8 || WC == 9) {
                        document.getElementById("WCv").value = 'High';
                    }
                </script>
            </table>

        </i>
    </center>
</body>

</html>
