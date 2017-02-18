<html>
<head>
<title>SUSY Processes</title>
<link rel="stylesheet" type="text/css" href="pythia.css"/>
<link rel="shortcut icon" href="pythia32.gif"/>
</head>
<body>

<script language=javascript type=text/javascript>
function stopRKey(evt) {
var evt = (evt) ? evt : ((event) ? event : null);
var node = (evt.target) ? evt.target :((evt.srcElement) ? evt.srcElement : null);
if ((evt.keyCode == 13) && (node.type=="text"))
{return false;}
}

document.onkeypress = stopRKey;
</script>
<?php
if($_POST['saved'] == 1) {
if($_POST['filepath'] != "files/") {
echo "<font color='red'>SETTINGS SAVED TO FILE</font><br/><br/>"; }
else {
echo "<font color='red'>NO FILE SELECTED YET.. PLEASE DO SO </font><a href='SaveSettings.php'>HERE</a><br/><br/>"; }
}
?>

<form method='post' action='SUSYProcesses.php'>

<h2>SUSY</h2>
<p>
Here is collected processes involving supersymmetric particle 
production, with the exception of the (extended) Higgs sector.
Since the number of separate but closely related processes is so big,
there will not be switches for each separate process but only for a
reasonable set of subgroups. However, the general 
switches <code>SUSY:idA</code> and <code>SUSY:idB</code> may be used in
conjunction with any of these groups to provide some additional
flexibility to concentrate on processes involving only specific (s)particle
final states, see below. 
</p>

<p>
Most of the SUSY implementation in PYTHIA 8 has been written by
N. Desai, in combination with an MCnet studentship at CERN in
2010, with further work having been carried out during 2011. 
The implementation is documented in [<a href="Bibliography.php" target="page">Des11</a>]. Please give due
credit to external contributions to PYTHIA 8, such as this one, by
including the original work in your list of references when using this
implementation. 
</p>

<p>Since the implementation of SUSY processes was only recently
completed [<a href="Bibliography.php" target="page">Des11</a>], case-by-case validations against other codes 
are still recommended. In all cases, a set of default validations have 
already been carried out by the authors. These validations compared to 
the PYTHIA 6 SUSY implementation, using an sps1a spectrum. Most of the
SLHA2-specific extensions have  not been explicitly validated,
however, with the exception of the R-parity violating single-sparticle
production cross sections.
</p>

<br/><b>Important Note on SLHA:</b> 
In order to simulate SUSY processes it is required to read in the 
couplings and masses relevant for the scenario to be studied. This 
is done with the help of the SUSY Les Houches Accord (SLHA), including
the SLHA2 extensions and generalizations. (Internally, the SLHA2
conventions are used. SLHA1 spectra are automatically translated into
SLHA2 notation during initialization.) The 
reading of a relevant SLHA file <b>must</b> be set up, as described 
on <?php $filepath = $_GET["filepath"];
echo "<a href='SUSYLesHouchesAccord.php?filepath=".$filepath."' target='page'>";?>the SLHA page</a>. 
Attempting to generate SUSY processes without a properly initialized
SLHA spectrum is strongly discouraged and may lead to unexpected
results. Always check for warnings and errors reported by the SLHA
reader during the initialization stage. 

<h3>SUSY Processes</h3>

<br/><b>Note 1:</b> Decays of SUSY particles are described
separately <a href="#decays">below</a>. 

<br/><b>Note 2:</b> One special possibility is that the gluino or 
some squark(s) are sufficiently long-lived to hadronize. See 
<?php $filepath = $_GET["filepath"];
echo "<a href='RHadrons.php?filepath=".$filepath."' target='page'>";?>the R-hadrons page</a> for further details.

<br/><b>Note 3:</b> lepton- and photon-initial states are not yet available. 
Only quark/gluon-initiated <i>2 -> 2</i> and <i>2 -> 1</i> (RPV) 
processes have been implemented. Likewise, direct slepton production 
has not yet been implemented (i.e., <i>2 -> 2</i> processes
involving sleptons in the final state). Sleptons will of course still be
produced through cascade decays of heavier (s)particles. 

<br/><b>Note 4:</b> cross sections will be correctly folded with open
branching fractions of cascade decays, but at present any difference between
particle and antiparticle decay tables is not taken into account. This
possibility will be included in a future update. 

<br/><br/><strong>SUSY:all</strong>  <input type="radio" name="1" value="on"><strong>On</strong>
<input type="radio" name="1" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
Common switch for production of supersymmetric particles, i.e. 
particles with R-parity -1. 
  

<br/><br/><table><tr><td><strong>SUSY:idA  </td><td></td><td> <input type="text" name="2" value="0" size="20"/>  &nbsp;&nbsp;(<code>default = <strong>0</strong></code>; <code>minimum = 0</code>)</td></tr></table>
Option to limit the sum over possible outgoing states in SUSY
<i>2 -> 2</i> processes to ones including a specific particle 
identity code. The default corresponds to summing over all possible 
indices. A non-zero value of <code>SUSY:idA</code> selects only processes 
that contain the state corresponding to that particular particle identity 
code in the fundamental <i>2 -> 2</i> scattering process (symmetrized 
over particle/antiparticle). It is the user's responsibility to ensure 
that (a subset of) the processes be to simulated actually include this 
particle at the <i>2 -> 2</i> level; thus, asking for the lightest 
neutralino (code 1000021) to be present in a squark-squark production 
process will give no match. 
  

<br/><br/><table><tr><td><strong>SUSY:idB  </td><td></td><td> <input type="text" name="3" value="0" size="20"/>  &nbsp;&nbsp;(<code>default = <strong>0</strong></code>; <code>minimum = 0</code>)</td></tr></table>
As for <code>SUSY:idA</code>, but requires an additional particle
with PDG code <code>SUSY:idB</code> to be present in the <i>2 -> 2</i>
process. Thus, using  <code>SUSY:idA</code> and  <code>SUSY:idB</code> 
a specific subprocess can be selected. Again only the absolute sign is 
used, i.e. the summation over particle and antiparticle is retained.
  

<br/><br/><table><tr><td><strong>SUSY:sin2thetaWMode  </td><td>  &nbsp;&nbsp;(<code>default = <strong>2</strong></code>; <code>minimum = 1</code>; <code>maximum = 3</code>)</td></tr></table>
The value of <ei>sin2(thetaW)</ei> should be taken from
<br/>
<input type="radio" name="4" value="1"><strong>1 </strong>: SM value, defined at <ei>M_Z</ei>, taken from PYTHIA's <code>StandardModel:sin2thetaW</code> parameter.<br/>
<input type="radio" name="4" value="2" checked="checked"><strong>2 </strong>: SUSY value, defined at <ei>M_SUSY</ei>, derived from the running gauge couplings in <code>BLOCK GAUGE</code> in the SLHA file. Note: if no such block is present in the input file, this option will default back to option 1 above, i.e., the SM value.<br/>
<input type="radio" name="4" value="3"><strong>3 </strong>: Pole value, defined by <ei>1 - M_W^2/M_Z^2</ei>,  using the pole masses stored in the SLHA <code>BLOCK MASS</code>, or,  alternatively, PYTHIA's internal pole masses if no such block is  present.<br/>

<h4>Gluino Pair Production</h4>

<br/><br/><strong>SUSY:gg2gluinogluino</strong>  <input type="radio" name="5" value="on"><strong>On</strong>
<input type="radio" name="5" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
Pair production of gluinos by gluon-gluon initial states. 
  

<br/><br/><strong>SUSY:qqbar2gluinogluino</strong>  <input type="radio" name="6" value="on"><strong>On</strong>
<input type="radio" name="6" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
Pair production of gluinos by quark-antiquark annihilation and
<i>t</i>-channel squark exchange. So far, these cross sections assume the
squark CKM is aligned with the quark CKM and that all quantities are
real, so effects of non-minimal flavour violation and/or CP violation
are not yet included.
  

<h4>Associated Squark-Gluino Production</h4>

<br/><br/><strong>SUSY:qg2squarkgluino</strong>  <input type="radio" name="7" value="on"><strong>On</strong>
<input type="radio" name="7" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
Associated production of a squark with a gluino. Only implemented for the
flavor-diagonal case. So far, these cross sections assume the
squark CKM is aligned with the quark CKM and that all quantities are
real, so effects of non-minimal flavour violation and/or CP violation
are not yet included.
  

<h4>Squark Pair Production</h4>

<br/><br/><strong>SUSY:gg2squarkantisquark</strong>  <input type="radio" name="8" value="on"><strong>On</strong>
<input type="radio" name="8" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
Pair production of a scalar quark together with a scalar antiquark by
gluon annhilation via <i>s</i>-channel gluon exhange, <i>t</i>- and 
<i>u</i>-channel squark exchange, and the direct 4-point coupling. 
The cross section expression follows [<a href="Bibliography.php" target="page">Boz07</a>]. 
Validation of the FLV and CPV cases has not yet been completed.
  

<br/><br/><strong>SUSY:qqbar2squarkantisquark</strong>  <input type="radio" name="9" value="on"><strong>On</strong>
<input type="radio" name="9" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
Pair production of a scalar quark together with a scalar antiquark 
by quark-antiquark annihilation. 
For same-isospin <i>~q~q*</i> production (i.e., <i>~u~u*</i>, 
<i>~u~c*</i>, ...), the <i>s</i>-channel gluon, photon, and 
<i>Z</i> and <i>t</i>-channel gluino contributions have so far 
been implemented (i.e., the <i>t</i>-channel neutralino contributions 
are neglected). For opposite-isospin <i>~q~q*</i> production 
(<i>~u~d*</i>, <i>~u~s*</i>, ...), the <i>s</i>-channel <i>W</i> 
and <i>t</i>-channel gluino contributions have been implemented 
(i.e., the <i>t</i>-channel neutralino contributions are neglected). 
The cross section expressions follow [<a href="Bibliography.php" target="page">Boz07</a>]. 
Validation of the FLV and CPV cases has not yet been completed.
(Note to PYTHIA 6 users: 
in older PYTHIA 6 versions, a bug caused the <i>~t1~t2*</i> cross to be
overcounted by a factor of 2. Starting from version 6.4.24, that
generator now agrees with the implementation here.)
  

<br/><br/><strong>SUSY:qqbar2squarkantisquark:onlyQCD</strong>  <input type="radio" name="10" value="on"><strong>On</strong>
<input type="radio" name="10" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
When switched <code>on</code> this flag switches off all but the 
<i>s</i>-channel gluon contribution in the
calculation of same-isospin squark-antisquark production cross
sections. Intended for reference only. For the most
accurate physics simulation, leave this flag in the <code>off</code>
position.  
  

<br/><br/><strong>SUSY:qq2squarksquark</strong>  <input type="radio" name="11" value="on"><strong>On</strong>
<input type="radio" name="11" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
Pair production of scalar quarks (squark-squark and its charge
conjugate process; for squark-antisquark production see above) 
by <i>t</i>- and <i>u</i>-channel gluino, neutralino, and 
chargino exchange. The cross section expressions follow [<a href="Bibliography.php" target="page">Boz07</a>]. 
Validation of the FLV and CPV cases has not yet been completed.
(Note to PYTHIA 6 users: PYTHIA 6 only included the gluino exchange
contribution, which typically dominates due to the size of the strong
coupling; for counterchecks, 
the flag <code>SUSY:qq2squarksquark:onlyQCD</code>
below can be switched on to eliminate the chargino and neutralino
contributions.)
  

<br/><br/><strong>SUSY:qq2squarksquark:onlyQCD</strong>  <input type="radio" name="12" value="on"><strong>On</strong>
<input type="radio" name="12" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
When switched <code>on</code> this flag causes the <i>t</i>- or 
<i>u</i>-channel neutralino and chargino contributions to be 
ignored in the calculation of squark pair production cross sections. 
Intended for reference only. For the most accurate physics simulation, 
leave this flag in the <code>off</code> position.  
  

<h4>Neutralino and Chargino Pair Production</h4>

<br/><br/><strong>SUSY:qqbar2chi0chi0</strong>  <input type="radio" name="13" value="on"><strong>On</strong>
<input type="radio" name="13" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
Pair production of neutralinos by quark-antiquark annihilation. With
four neutralino species this gives ten separate processes, codes 
1201 - 1210. The cross section expressions follow [<a href="Bibliography.php" target="page">Boz07</a>].
Validation of the FLV and CPV cases has not yet been completed.
  

<br/><br/><strong>SUSY:qqbar2chi+-chi0</strong>  <input type="radio" name="14" value="on"><strong>On</strong>
<input type="radio" name="14" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
Associated chargino-neutralino production by quark-antiquark
annihilation. With four neutralino species, two chargino ones, and
maintaining charge conjugate proceeses separate, this gives 16 
separate processes, codes 1221 - 1236. The cross section expressions 
follow [<a href="Bibliography.php" target="page">Boz07</a>].
Validation of the FLV and CPV cases has not yet been completed.
  

<br/><br/><strong>SUSY:qqbar2chi+chi-</strong>  <input type="radio" name="15" value="on"><strong>On</strong>
<input type="radio" name="15" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
Pair production of charginos by quark-antiquark annihilation. With
two chargino species and maintaining mutually charge conjugate
processes separate, this gives four separate processes, codes 
1241 - 1244. The cross section expressions follow [<a href="Bibliography.php" target="page">Boz07</a>]. 
Validation of the FLV and CPV cases has not yet been completed.
  

<h4>Associated Neutralino/Chargino + Squark/Gluino Production</h4>

<br/><br/><strong>SUSY:qg2chi0squark</strong>  <input type="radio" name="16" value="on"><strong>On</strong>
<input type="radio" name="16" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
Pair production of neutralinos from quark-gluon initial states.
The cross section expressions follow [<a href="Bibliography.php" target="page">Boz07</a>].
Validation of the FLV and CPV cases has not yet been completed.
  

<br/><br/><strong>SUSY:qg2chi+-squark</strong>  <input type="radio" name="17" value="on"><strong>On</strong>
<input type="radio" name="17" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
Associated chargino-squark production from quark-gluon initial states.
annihilation. The cross section expressions
follow [<a href="Bibliography.php" target="page">Boz07</a>].
Validation of the FLV and CPV cases has not yet been completed.
  

<br/><br/><strong>SUSY:qqbar2chi0gluino</strong>  <input type="radio" name="18" value="on"><strong>On</strong>
<input type="radio" name="18" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
Associated neutralino-gluino production by quark-antiquark
annihilation. Status: not implemented yet.
  

<br/><br/><strong>SUSY:qqbar2chi+-gluino</strong>  <input type="radio" name="19" value="on"><strong>On</strong>
<input type="radio" name="19" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
Associated chargino-gluino production by quark-antiquark
annihilation. Status: not implemented yet.
  

<h4>Slepton Production</h4>

No 2->2 slepton pair production or associated slepton production 
cross sections have been implemented yet. 

<h4>R-parity violating squark production</h4>

<br/><br/><strong>SUSY:qq2antisquark</strong>  <input type="radio" name="20" value="on"><strong>On</strong>
<input type="radio" name="20" value="off" checked="checked"><strong>Off</strong>
 &nbsp;&nbsp;(<code>default = <strong>off</strong></code>)<br/>
Resonant squark production via R-parity violating UDD couplings. The
couplings must be input using the SLHA2 structure. 
  

<a name="decays"></a>
<h3>Decays of SUSY Particles</h3>

Based on the parameters read in from the SLHA, PYTHIA 8 will normally 
compute the decay modes of SUSY particles automatically, using the 
<code>SusyResonanceDecays</code> class(es). Essentially all tree-level
2-body decays in the MSSM  
have been implemented this way, excepting so far only those involving
Higgs bosons (either in the in- or out-state) or gravitinos. 
Available channels so far include:
<ul>
<li>~q &rarr; q + ~chi</li>
<li>~q &rarr; ~q + W/Z</li>
<li>~q &rarr; q + q (RPV UDD)</li>
<li>~q &rarr; l + q (RPV LQD)</li>
<li>~g &rarr; ~q + q</li>
<li>~chi &rarr; ~chi + Z/W</li>
<li>~chi &rarr; ~q + q</li>
<li>~chi &rarr; ~l/~nu + l/nu</li>
<li>~chi0 &rarr; q + q + q (RPV UDD)</li>
<li>~l/~nu &rarr; l/nu + ~chi</li>
<li>~l/~nu &rarr; ~l/~nu + W/Z</li>
</ul>
All channels are still undergoing validation, so this
implementation should be considered preliminary.
Still missing but to be included in a forthcoming update
are: 3-body decays of charginos (via RPV), and 2-body decays of squarks and
gauginos with Higgs as one of the decay products. 

<input type="hidden" name="saved" value="1"/>

<?php
echo "<input type='hidden' name='filepath' value='".$_GET["filepath"]."'/>"?>

<table width="100%"><tr><td align="right"><input type="submit" value="Save Settings" /></td></tr></table>
</form>

<?php

if($_POST["saved"] == 1)
{
$filepath = $_POST["filepath"];
$handle = fopen($filepath, 'a');

if($_POST["1"] != "off")
{
$data = "SUSY:all = ".$_POST["1"]."\n";
fwrite($handle,$data);
}
if($_POST["2"] != "0")
{
$data = "SUSY:idA = ".$_POST["2"]."\n";
fwrite($handle,$data);
}
if($_POST["3"] != "0")
{
$data = "SUSY:idB = ".$_POST["3"]."\n";
fwrite($handle,$data);
}
if($_POST["4"] != "2")
{
$data = "SUSY:sin2thetaWMode = ".$_POST["4"]."\n";
fwrite($handle,$data);
}
if($_POST["5"] != "off")
{
$data = "SUSY:gg2gluinogluino = ".$_POST["5"]."\n";
fwrite($handle,$data);
}
if($_POST["6"] != "off")
{
$data = "SUSY:qqbar2gluinogluino = ".$_POST["6"]."\n";
fwrite($handle,$data);
}
if($_POST["7"] != "off")
{
$data = "SUSY:qg2squarkgluino = ".$_POST["7"]."\n";
fwrite($handle,$data);
}
if($_POST["8"] != "off")
{
$data = "SUSY:gg2squarkantisquark = ".$_POST["8"]."\n";
fwrite($handle,$data);
}
if($_POST["9"] != "off")
{
$data = "SUSY:qqbar2squarkantisquark = ".$_POST["9"]."\n";
fwrite($handle,$data);
}
if($_POST["10"] != "off")
{
$data = "SUSY:qqbar2squarkantisquark:onlyQCD = ".$_POST["10"]."\n";
fwrite($handle,$data);
}
if($_POST["11"] != "off")
{
$data = "SUSY:qq2squarksquark = ".$_POST["11"]."\n";
fwrite($handle,$data);
}
if($_POST["12"] != "off")
{
$data = "SUSY:qq2squarksquark:onlyQCD = ".$_POST["12"]."\n";
fwrite($handle,$data);
}
if($_POST["13"] != "off")
{
$data = "SUSY:qqbar2chi0chi0 = ".$_POST["13"]."\n";
fwrite($handle,$data);
}
if($_POST["14"] != "off")
{
$data = "SUSY:qqbar2chi+-chi0 = ".$_POST["14"]."\n";
fwrite($handle,$data);
}
if($_POST["15"] != "off")
{
$data = "SUSY:qqbar2chi+chi- = ".$_POST["15"]."\n";
fwrite($handle,$data);
}
if($_POST["16"] != "off")
{
$data = "SUSY:qg2chi0squark = ".$_POST["16"]."\n";
fwrite($handle,$data);
}
if($_POST["17"] != "off")
{
$data = "SUSY:qg2chi+-squark = ".$_POST["17"]."\n";
fwrite($handle,$data);
}
if($_POST["18"] != "off")
{
$data = "SUSY:qqbar2chi0gluino = ".$_POST["18"]."\n";
fwrite($handle,$data);
}
if($_POST["19"] != "off")
{
$data = "SUSY:qqbar2chi+-gluino = ".$_POST["19"]."\n";
fwrite($handle,$data);
}
if($_POST["20"] != "off")
{
$data = "SUSY:qq2antisquark = ".$_POST["20"]."\n";
fwrite($handle,$data);
}
fclose($handle);
}

?>
</body>
</html>

<!-- Copyright (C) 2012 Torbjorn Sjostrand -->

