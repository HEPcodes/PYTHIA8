<html>
<head>
<title>The Particle Data Scheme</title>
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

<form method='post' action='ParticleDataScheme.php'>

<h2>The Particle Data Scheme</h2>

The particle data scheme may take somewhat longer to understand than
the settings one. In particular the set of methods to access information
is rather more varied, to allow better functionality for advanced usage. 
However, PYTHIA does come with a sensible default set of particle 
properties and decay tables. Thus there is no need to learn any of the
methods on this page to get going. Only when you perceive a specific need
does it make sense to learn the basics.

<p/>
The central section on this page is the Operation one. The preceding 
sections are mainly there to introduce the basic structure and the set 
of properties that can be accessed. 

<h3>Databases</h3>

The management of particle data is based on the four classes:
<ul>
<li><code>ParticleDataEntry</code>, which stores the relevant information 
on a  particle species, and</li>
<li><code>ParticleDataTable</code>, which is a map of PDG particle 
<code>id</code> numbers [<a href="Bibliography.php" target="page">Yao06</a>] onto the relevant 
<code>ParticleDataEntry</code>.</li> 
<li><code>DecayChannel</code>, which stores info on one particular decay 
mode.</li>
<li><code>DecayTable</code>, which is a vector of 
<code>DecayChannel</code>'s, containing all the decay modes of a 
particle, and also methods for picking a decay mode.</li>
</ul>
The objects of these classes together form a database that is 
continuously being used as the program has to assign particle masses, 
decay modes etc.

<p/>
The <code>ParticleDataTable</code> class is purely static, i.e. you 
can interact with it directly by 
<code>ParticleDataTable::command(argument)</code>. 
However, a <code>particleData</code> object of the 
<code>ParticleDataTable</code> class is a public member of the 
<code>Pythia</code> class, so an alternative 
notation would be <code>pythia.particleData.command(argument)</code>, 
assuming that <code>pythia</code> is an instance of the 
<code>Pythia</code> class. Further, for some of the most frequent user 
tasks, <code>Pythia</code> methods have been defined, so that 
<code>pythia.command(argument)</code> 
would work, see further below.

<p/>
A fundamental difference between the <code>ParticleData</code> 
classes and the <code>Settings</code> ones is that the former 
are accessed regularly during the event generation process, as a new 
particle is produced and its mass need to be set, e.g., while 
<code>Settings</code> is mainly/only used at the initialization stage. 
Nevertheless, it is not a good idea to change data in either of them 
in mid-run, since this may lead to inconsistencies. 

<h3>Stored properties for particles</h3>

Currently the following particle properties are stored in the
<code>ParticleDataTable</code> for a given PDG particle identity code 
<code>id</code>, here presented by the name used to access this property: 

<p/><code>method&nbsp; </code><strong> name(id) &nbsp;</strong> <br/>
particle and antiparticle names are stored separately, the sign of 
<code>id</code> determines which of the two is returned, with 
<code>void</code> used to indicate the absence of an antiparticle. 
  

<p/><code>method&nbsp; </code><strong> spinType(id) &nbsp;</strong> <br/>
the spin type, of the form <i>2 s + 1</i>, with special code 0
for entries of unknown or indeterminate spin.
  

<p/><code>method&nbsp; </code><strong> chargeType(id) &nbsp;</strong> <br/>
three times the charge (to make it an integer), taking into account
the sign of <code>id</code>.
  

<p/><code>method&nbsp; </code><strong> colType(id) &nbsp;</strong> <br/>
the colour type, with 0 uncoloured, 1 triplet, -1 antitriplet and 2 
octet, taking into account the sign of <code>id</code>.
  

<p/><code>method&nbsp; </code><strong> m0(id) &nbsp;</strong> <br/>
the nominal mass <i>m_0</i> (in GeV).
  

<p/><code>method&nbsp; </code><strong> mWidth(id) &nbsp;</strong> <br/>
the width <i>Gamma</i> of the Breit-Wigner distribution (in GeV).
  

<p/><code>method&nbsp; </code><strong> mMin(id), mMax(id) &nbsp;</strong> <br/>
the lower and upper limit, respectively, of the allowed mass range 
generated by the Breit-Wigner (in GeV). If <br/><i>mMax &lt; mMin</i><br/> 
then no upper limit is imposed. Have no meanings for particles 
without width, and would typically be 0 there.
  

<p/><code>method&nbsp; </code><strong> tau0(id) &nbsp;</strong> <br/>
the nominal proper lifetime <i>tau_0</i> (in mm/c).
  

<p/><code>method&nbsp; </code><strong> isResonance(id) &nbsp;</strong> <br/>
a flag telling whether a particle species are considered as a resonance 
or not. Here "resonance" is used as shorthand for any massive particle 
where the decay process should be counted as part of the hard process
itself, and thus be performed before showers and other event aspects 
are added. Restrictions on allowed decay channels is also directly
reflected in the cross section of simulated processes, while those of
normal hadrons and other light particles are not.
In practice, it is reserved for states above the <i>b bbar</i> 
bound systems in mass, i.e. for <i>W, Z, t</i>, Higgs states, 
supersymmetric states and (most?) other states in any new theory. 
All particles with <code>m0</code> above 20 GeV are by default
initialized to be considered as resonances. 
  

<p/><code>method&nbsp; </code><strong> mayDecay(id) &nbsp;</strong> <br/>
a flag telling whether a particle species may decay or not, offering 
the main user switch. Whether a given particle of this kind then actually 
will decay also depends on it having allowed decay channels, and on
other flags for <?php $filepath = $_GET["filepath"];
echo "<a href='ParticleDecays.php?filepath=".$filepath."' target='page'>";?>particle decays</a>. 
All particles with <code>tau0</code> below 1000 mm are 
by default initialized to allow decays.
  

<p/><code>method&nbsp; </code><strong> externalDecay(id) &nbsp;</strong> <br/>
a flag telling whether a particle should be handled by an external 
decay package or not, with the latter default. Can be manipulated as 
described on this page, but should normally not be. Instead the
<?php $filepath = $_GET["filepath"];
echo "<a href='ExternalDecays.php?filepath=".$filepath."' target='page'>";?><code>pythia.decayPtr</code></a> 
method should be provided with the list of relevant particles.
  

<p/><code>method&nbsp; </code><strong> isVisible(id) &nbsp;</strong> <br/>
a flag telling whether a particle species is to be considered as
visible in a detector or not, as used e.g. in analysis routines.
By default this includes neutrinos and a few BSM particles
(gravitino, sneutrinos, neutralinos) that have neither strong nor
electromagnetic charge, and are not made up of constituents that
have it. The value of this flag is only relevant if a particle is
long-lived enough actually to make it to a detector.
  

<p/>
Similarly-named methods can also be used to set these properties. 
We do not provide the details here, since other methods to be 
introduced below are the ones likely to be used for such tasks. 
(Normally the correspondence is obvious in the header file, but 
for the name you either can use two methods to set name and
antiparticle name separately, or use one method that takes them
both as input.) 

<p/>
There are some further methods for output only, i.e. properties
that cannot be set directly:

<p/><code>method&nbsp; </code><strong> particleDataPtr(id) &nbsp;</strong> <br/>
returns a pointer to the <code>ParticleDataEntry</code> object.
  

<p/><code>method&nbsp; </code><strong> hasAnti(id) &nbsp;</strong> <br/>
bool whether a distinct antiparticle exists or not. Is true if an
antiparticle name has been set (and is different from 
<code>void</code>).
  

<p/><code>method&nbsp; </code><strong> charge(id) &nbsp;</strong> <br/>
the electrical charge of a particle, as a <code>double</code> equal 
to <code>chargeType(id)/3</code>.

<p/><code>method&nbsp; </code><strong> mass(id) &nbsp;</strong> <br/>
returns a mass distributed according to a truncated Breit-Wigner, 
with parameters as above (see also the 
<code>ParticleData:modeBreitWigner</code> switch). Is equal to 
<code>m0(id)</code> for particles without width. 
  

<p/><code>method&nbsp; </code><strong> constituentMass(id) &nbsp;</strong> <br/>
is the constituent mass for a quark, hardcoded as 
<i>m_u = m_d = 0.325</i>, <i>m_s = 0.50</i>, <i>m_c = 1.60</i> 
and <i>m_b = 5.0</i> GeV, for a diquark the sum of quark constituent 
masses, and for everything else the same as the ordinary mass.
  

<p/><code>method&nbsp; </code><strong> m0Min(id), m0Max(id) &nbsp;</strong> <br/>
similar to <code>mMin()</code> and <code>mMax()</code>, except that
for particles with no width the <code>m0(id)</code> value is returned.
  

<p/><code>method&nbsp; </code><strong> isLepton(id) &nbsp;</strong> <br/>
true for a lepton or an antilepton (including neutrinos).
  

<p/><code>method&nbsp; </code><strong> isQuark(id) &nbsp;</strong> <br/>
true for a quark or an antiquark.
  

<p/><code>method&nbsp; </code><strong> isGluon(id) &nbsp;</strong> <br/>
true for a gluon.
  

<p/><code>method&nbsp; </code><strong> isHadron(id) &nbsp;</strong> <br/>
true for a hadron (made up out of normal quarks and gluons, 
i.e. not for R-hadrons and other exotic states).
  

<p/><code>method&nbsp; </code><strong> heaviestQuark(id) &nbsp;</strong> <br/>
extracts the heaviest quark or antiquark, i.e. one with largest 
<code>id</code> number, for a hadron. 
  

<h3>Stored properties for decays</h3>

The following properties are stored for each decay channel:

<p/><code>method&nbsp; </code><strong> onMode() &nbsp;</strong> <br/>
0 if a channel is off,<br/>
1 if on,<br/>
2 if on for a particle but off for an antiparticle,<br/>
3 if on for an antiparticle but off for a particle.<br/>
If a particle is its own antiparticle then 2 is on and 3 off.<br/>
This option can be used e.g. to encode CP violation in B decays.
  

<p/><code>method&nbsp; </code><strong> bRatio() &nbsp;</strong> <br/>
the branching ratio.
  

<p/><code>method&nbsp; </code><strong> meMode() &nbsp;</strong> <br/>
the mode of processing this channel, possibly with matrix elements 
(see the <?php $filepath = $_GET["filepath"];
echo "<a href='ParticleDecays.php?filepath=".$filepath."' target='page'>";?>particle decays</a> description);
  

<p/><code>method&nbsp; </code><strong> multiplicity() &nbsp;</strong> <br/>
the number of decay products in a channel, at most 8.
(Is not set as such, but obtained from the products list below.)
  

<p/><code>method&nbsp; </code><strong> product(i) &nbsp;</strong> <br/>
a list of the decay products, 8 products 0 &lt;= i &lt; 8,
with trailing unused ones set to 0.
  

<p/>
The decay table, a vector of decay channels, also defines a 
few methods:

<p/><code>method&nbsp; </code><strong> addChannel( branchingRatio, meMode, product1, ...) &nbsp;</strong> <br/>
adds a decay channel with up to 8 products.
  

<p/><code>method&nbsp; </code><strong> size() &nbsp;</strong> <br/>
gives the number of decay channels for a particle.
  

<p/><code>method&nbsp; </code><strong> rescaleBR(newSumBR) &nbsp;</strong> <br/>
rescale all branching ratios to the provided new sum, 
by default unity. 
  

<p/><code>method&nbsp; </code><strong> pick() &nbsp;</strong> <br/>
picks one decay channel according to their respective branching 
ratios.
  

<p/><code>method&nbsp; </code><strong> dynamicPick() &nbsp;</strong> <br/>
intended for resonances specifically, this picks one decay channel 
according to the respective partial widths for the specific mass
value of the resonance; assumes that the partial widths are input
beforehand, using a special <code>dynamicBR()</code> method.
  

<h3>Operation</h3>

The normal flow of the particle data operations is:

<ol>

<li>
When a <code>Pythia</code> object <code>pythia</code> is created, the 
<code>ParticleDataTable</code> member <code>pythia.particleData</code> 
is asked to scan the <code>ParticleData.xml</code> file.

<p/>
All lines beginning with <code>&lt;particle</code> are scanned for 
information on a particle species, and all lines beginning with 
<code>&lt;channel</code> are assumed to contain a decay channel of the 
enclosing particle. In both cases XML syntax is used, with attributes
used to identify the stored properties, and with omitted properties
defaulting back to 0 where meaningful. The particle and channel 
information may be split over several lines, up to the &gt; endtoken. 
The format of a <code>&lt;particle</code> tag is:
<pre>
    &lt;particle id="..." name="..." antiName="..." spinType="..." chargeType="..." colType="..." 
       m0="..." mWidth="..." mMin="..." mMax="..." tau0="..."&gt;
    &lt;/particle&gt;
</pre>
where the fields are the properties already introduced above.
Note that <code>isResonance</code>, <code>mayDecay</code>, 
<code>externalDecay</code> and <code>isVisible</code>
are not set here, but are provided with default values by the rules 
described above. Once initialized, also these latter properties can be
changed, see below.<br/> 

The format of  a <code>&lt;channel></code> tag is:
<pre>
    &lt;channel onMode="..." bRatio="..." meMode="..." products="..." /&gt;
</pre>
again see properties above. The products are given as a blank-separated 
list of <code>id</code> codes.  
<br/><b>Important</b>: the values in the <code>.xml</code> file should not 
be changed, except by the PYTHIA authors. Any changes should be done 
with the help of the methods described below.
</li> 

<li> <p/>
Between the creation of the <code>Pythia</code> object and the 
<code>init</code> call for it, you may use the methods of the 
<code>ParticleDataTable</code> class to modify some of the default values. 
Several different approaches can be chosen for this.

<p/> 
a) Inside your main program you can directly set values with
<pre>
    pythia.readString(string);
</pre>
where both the variable name and the value are contained inside
the character string, separated by blanks and/or a =, e.g. 
<pre>
    pythia.readString("111:mayDecay = off"); 
</pre>
switches off the decays of the <i>pi^0</i>.<br/>    

The particle id (> 0) and the property to be changed must be given, 
separated by a colon.<br/> 

The allowed properties are: <code>name</code>, <code>antiName</code>, 
<code>spinType</code>, <code>chargeType</code>, <code>colType</code>, 
<code>m0</code>, <code>mWidth</code>, <code>mMin</code>, 
<code>mMax</code>, <code>tau0</code>, <code>isResonance</code>,
<code>mayDecay</code>, <code>externalDecay</code> and
<code>isVisible</code>. All of these names are case-insensitive. 
Names that do not match an existing variable 
are ignored. A warning is printed, however, unless an optional 
second argument <code>false</code> is used.<br/> 
Strings beginning with a non-alphanumeric character, like # or !, 
are assumed to be comments and are not processed at all. For 
<code>bool</code> values, the following notation may be used 
interchangeably: <code>true = on = yes = ok = 1</code>, while everything 
else gives <code>false</code> (including but not limited to 
<code>false</code>, <code>off</code>, <code>no</code> and 0).

<p/>
Particle data often comes in sets of closely related information.
Therefore some properties expect the value to consist of several
numbers. These can then be separated by blanks (or by commas). 
A simple example is <code>names</code>, which expects both the 
name and antiname to be given. A more interesting one is the 
<code>all</code> property,
<pre>  
    id:all = name antiName spinType chargeType colType m0 mWidth mMin mMax tau0
</pre>
where all the current information on the particle itself is replaced, 
but any decay channels are kept unchanged. Using <code>new</code> instead 
of <code>all</code> also removes any previous decay channels.   
As before, <code>isResonance</code>, <code>mayDecay</code>,
<code>externalDecay</code> and <code>isVisible</code> are (re)set to 
their default values, and would have to be changed separately if required.

<p/>
A further command is <code>rescaleBR</code>, which rescales each of the
existing branching ratios with a common factor, such that their new
sum is the provided value. This may be a first step towards adding 
new decay channels, see further below. 

<p/>
Alternatively the <code>id</code> code may be followed by another integer, 
which then gives the decay channel number. This then has to be 
followed by the property specific to this channel, either 
<code>onMode</code>, <code>bRatio</code>, <code>meMode</code> or 
<code>products</code>. In the latter case all the products of the channel 
should be given:
<pre>
    id:channel:products =  product1 product2 ....  
</pre>
The line will be scanned until the end of the line, or until a
non-number word is encountered, or until the maximum allowed number
of eight products is encountered, whichever happens first. It is also 
possible to replace all the properties of a channel in a similar way:
<pre>
    id:channel:all = onMode bRatio meMode product1 product2 ....  
</pre>
To add a new channel at the end, use
<pre>
    id:addChannel = onMode bRatio meMode product1 product2 ....
</pre>

<p/>
It is currently not possible to remove a channel selectively, but 
setting its branching ratio vanishing is as effective. If you want to
remove all existing channels and force decays into one new channel
you can use
<pre>
    id:oneChannel = onMode bRatio meMode product1 product2 ....
</pre>
 A first <code>oneChannel</code> command could be followed by
several subsequent <code>addChannel</code> ones, to build
up a completely new decay table for an existing particle.

<p/>
When adding new channels or changing branching ratios in general,
note that, once a particle is to be decayed, the sum of branching 
ratios is always rescaled to unity. Beforehand, <code>rescaleBR</code> 
may be used to rescale an existing branching ratio by the given factor.

<p/>
There are a few commands that will study all the decay channels of the
given particle, to switch them on or off as desired. The 
<pre>
    id:onMode = onMode
</pre> 
will set the <code>onMode</code> property of all channels to the 
desired value. The 
<pre> 
    id:onIfAny  = product1 product2 .... 
    id:offIfAny = product1 product2 .... 
</pre>
will set the <code>onMode</code> 1 or 0, respectively, for all channels
which contain any of the enumerated products, where the matching 
is done without distinction of particles and antiparticles. 
Correspondingly
<pre> 
    id:onIfAll  = product1 product2 .... 
    id:offIfAll = product1 product2 .... 
</pre>
will set the <code>onMode</code> 1 or 0, respectively, for all channels
which contain all of the enumerated products, again without distinction 
of particles and antiparticles. If the same product appears twice in the
list it must also appear twice in the decay channel, and so on. 
The decay channel is allowed to contain further particles, beyond the 
product list. By contrast,
<pre> 
    id:onIfMatch  = product1 product2 .... 
    id:offIfMatch = product1 product2 .... 
</pre>
requires the decay-channel multiplicity to agree with that of the product
list, but otherwise works as the <code>onIfAll/offIfAll</code> methods.
Neither of these methods can be used to set different decays for 
particles and antiparticles.

<p/>
Note that the action of several of the commands depends on the order
in which they are executed, as one would logically expect. For instance, 
<code>id:oneChannel</code> removes all decay channels of <code>id</code>
and thus all previous changes in this decay table, while subsequent
additions or changes would still take effect. Another example would be that
<code>23:onMode = off</code> followed by <code>23:onIfAny = 1 2 3 4 5<code>
would let the <i>Z^0</i> decay to quarks, while no decays would be
allowed if the order were to be reversed.   

<p/> 
b) The <code>Pythia</code> <code>readString</code> method actually does
not do changes itself, but sends on the string either to the
<code>ParticleData</code> class or to the <code>Settings</code> one. 
If desired, it is possible to communicate directly with the corresponding 
<code>ParticleData</code> method:
<pre>
    pythia.particleData.readString("111:mayDecay = off"); 
    pythia.particleData.readString("15:2:products = 16 -211"); 
</pre>
In this case, changes intended for <code>Settings</code> would not be 
understood.

<p/>
c) Underlying this are commands for all the individual properties in  
the <code>ParticleDataTable</code> class, one for each. Thus, an example 
now reads 
<pre>
    pythia.particleData.mayDecay(111, false);
</pre>
Boolean values should here be given as <code>true</code> or 
<code>false</code>. 

<p/>
d) A simpler and more useful way is to collect all your changes
in a separate file, with one line per change, e.g. 
<pre>
    111:mayDecay = off
</pre>
Each line is process as described for the string in 2a).<br/>

The file can be read by the 
<pre>
    pythia.readFile("filename"); 
</pre>
method. This file can freely mix 
commands to the <code>Settings</code> and <code>ParticleData</code>
 classes.
</li> 

<li> <p/>
A routine <code>reInit("filename")</code> is provided, and can be used to 
zero the particle data table and reinitialize  from scratch. Such a call 
might be required if several <code>Pythia</code> objects are created in the 
same run, and requested to have different values - by default the 
<code>init()</code> call is only made the first time. Several 
<code>pythia</code> with different values would have to run sequentially 
and not in parallel, though; recall that there is only one instance of 
the particle data table.
</li> 

<li> <p/>
You may at any time obtain a listing of all the particle data by calling
<pre>
    pythia.particleData.listAll();
</pre>
The listing is by increasing <code>id</code> number. It shows the basic 
quantities introduced above. Some are abbreviated in the header to fit on
the lines: <code>spn = spinType</code>, <code>chg = chargeType</code>, 
<code>col = colType</code>, <code>res = isResonance</code>, 
<code>dec = mayDecay && canDecay</code> (the latter checks that decay 
channels have been defined), <code>ext = externalDecay</code> and
<code>vis = isVisible</code>.<br/>

To list only those particles that were changed (one way or another, the 
listing will not tell what property or decay channel was changed), instead use
<pre>
    pythia.particleData.listChanged();
</pre>
(This info is based on a further <code>hasChanged</code> flag of a particle
or a channel, set <code>true</code> whenever any of the changing methods are 
used. It is possible to manipulate this value, but this is not recommended.) 
<br/>

To list only one particle, give its <code>id</code> code as argument to
the <code>list(...)</code> function.. To list a restricted set of particles, 
give in their <code>id</code> codes to <code>list(...)</code> as a 
<code>vector&lt;int></code>.
</li> 

<li> <p/>
For wholesale changes of particle properties all available data can be 
written out, edited, and then read back in again. These methods are
mainly intended for expert users. You can choose between two alternative
syntaxes.

<p/>
a) XML syntax, using the <code>&lt;particle</code> and 
<code>&lt;channel</code> lines already described. You use the method
<code>particleData.listXML(filename)</code> to produce such an XML
file and <code>particleData.readXML(filename)</code> to read it back
in after editing.

<p/>
b) Fixed/free format, using exactly the same information as illustrated
for the <code>&lt;particle</code> and <code>&lt;channel</code> lines
above, but now without any tags. This means that all information fields
must be provided (if there is no antiparticle then write 
<code>void</code>), in the correct order (while the order is irrelevant 
with XML syntax), and all on one line. Information is written out in 
properly lined-up columns, but the reading is done using free format, 
so fields need only be separated by at least one blank. Each new particle 
is supposed to be separated by (at least) one blank line, whereas no
blank lines are allowed between the particle line and the subsequent 
decay channel lines, if any.  You use the method
<code>particleData.listFF(filename)</code> to produce such a fixed/free
file and <code>particleData.readFF(filename)</code> to read it back
in after editing.

<p/>
As an alternative to the <code>readXML</code> and <code>readFF</code> 
methods you can also use the 
<code>particleData.reInit(filename, xmlFormat)</code> method, where
<code>xmlFormat = true</code> (default) corresponds to reading an XML
file and <code>xmlFormat = false</code> to a fixed/free format one.

<p/>
To check that the new particle and decay tables makes sense, you can use 
the <code>particleData.checkTable()</code> method, which will
go through all particles and warn if a number of problems are encountered,
such as inconsistent use of charge in particle names, inconsistent setup
of mass, mass range, width and lifetime, sum of branching ratios
not unity (allowed but discouraged) or charge not conserved in a decay 
channel. Several options exist to give various levels of verbosity.
Warnings should be viewed as reasons to check further, but need not 
indicate a true problem, and also not all problems may be caught.
</li>

</ol>

</body>
</html>

<!-- Copyright C 2007 Torbjorn Sjostrand -->
