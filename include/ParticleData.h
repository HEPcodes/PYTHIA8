// Header file for the classes containing particle data.
// DecayChannel contains info on a single decay channel.
// DecayTable contains all decay channels of a particle.
// ParticleDataEntry contains info on a single particle species.
// ParticleDataTable  collects info on all particles as a map.
// Copyright C 2007 Torbjorn Sjostrand

#ifndef Pythia8_ParticleData_H
#define Pythia8_ParticleData_H

#include "Basics.h"
#include "Information.h"
#include "PythiaStdlib.h"
#include "Settings.h"

namespace Pythia8 {

//**************************************************************************

// This class holds info on a single decay channel.

class DecayChannel {

public:
  // Constructor.
  DecayChannel(int onModeIn = 0, double bRatioIn = 0., int meModeIn = 0, 
    int prod0 = 0, int prod1 = 0, int prod2 = 0, int prod3 = 0, 
    int prod4 = 0, int prod5 = 0, int prod6 = 0, int prod7 = 0) 
    : onModeSave(onModeIn), bRatioSave(bRatioIn), dynamicBRSave(0.), 
    meModeSave(meModeIn), nProd(0), hasChangedSave(true) {
    prod[0] = prod0; prod[1] = prod1; prod[2] = prod2; prod[3] = prod3; 
    prod[4] = prod4; prod[5] = prod5; prod[6] = prod6; prod[7] = prod7; 
    for (int j = 0; j < 8; ++j) if (prod[j] != 0 && j == nProd) ++nProd; }  

  // Member functions for input.
  void onMode(int onModeIn) {onModeSave = onModeIn; hasChangedSave = true;}
  void bRatio(double bRatioIn) {bRatioSave = bRatioIn; hasChangedSave = true;}
  void rescaleBR(double fac) {bRatioSave *= fac; hasChangedSave = true;} 
  void meMode(int meModeIn) {meModeSave = meModeIn; hasChangedSave = true;} 
  void multiplicity(int multIn)  {nProd = multIn; hasChangedSave = true;} 
  void product(int i, int prodIn) {prod[i] = prodIn; nProd = 0;
    for (int j = 0; j < 8; ++j) if (prod[j] != 0 && j == nProd) ++nProd;   
    hasChangedSave = true;}
  void setHasChanged(bool hasChangedIn) {hasChangedSave = hasChangedIn;}

  // Member functions for output.
  int onMode() const {return onModeSave;}
  double bRatio() const {return bRatioSave;}
  int meMode() const {return meModeSave;}
  int multiplicity() const {return nProd;} 
  int product(int i) const {return (i >= 0 && i < nProd) ? prod[i] : 0;} 
  int productForce(int i) const {return (i >= 0 && i <8) ? prod[i] : 0;} 
  bool hasChanged() const { return hasChangedSave;}

  // Check for presence of particles anywhere in decay list.
  bool contains(int id1) const;
  bool contains(int id1, int id2) const;
  bool contains(int id1, int id2, int id3) const;

  // Input/output for dynamic selection of resonance decay mode.
  void dynamicBR(double dynamicBRIn) {dynamicBRSave = dynamicBRIn;}
  double dynamicBR() const {return dynamicBRSave;}

private:

  // Decay channel info.
  int onModeSave;
  double bRatioSave, dynamicBRSave;
  int meModeSave, nProd, prod[8];
  bool hasChangedSave;

};

//**************************************************************************

// This class holds info on all decay channels of a particle.

class DecayTable {

public:

  // Constructor.
  DecayTable() {}

  // Overload index operator to access a channel in the decay table.
  DecayChannel& operator[](int i){return channel[i];}
  const DecayChannel& operator[](int i) const {return channel[i];}

  // Add a decay channel to the decay table.
  void addChannel(int onMode = 0, double bRatio = 0., int meMode = 0, 
    int prod0 = 0, int prod1 = 0, int prod2 = 0, int prod3 = 0, 
    int prod4 = 0, int prod5 = 0, int prod6 = 0, int prod7 = 0) { 
    channel.push_back( DecayChannel( onMode, bRatio, meMode, prod0, 
    prod1, prod2, prod3, prod4, prod5, prod6, prod7) ); }

  // Decay table size.
  int size() const {return channel.size();}

  // Reset to empty decay table..
  void clear() {channel.resize(0);}

  // Rescale sum of branching ratios to unity.
  void rescaleBR(double newSumBR = 1.);

  // Random choice of decay channel according to branching ratios.
  bool preparePick(int idIn);
  DecayChannel& pickChannel();

  // Ditto according to dynamically calculated branching ratios.
  DecayChannel& dynamicPick(int idIn);

private:

  // A vector containing all the decay channels of the particle.
  vector<DecayChannel> channel;

  // Summed branching ratio of open channels.
  int idSave;
  double sumBR;

};

//**************************************************************************

// This class holds info on a single particle species.

class ParticleDataEntry {

public:

  // Constructors: for antiparticle exists or not.
  ParticleDataEntry(int idIn = 0, string nameIn = " ", 
    int spinTypeIn = 0, int chargeTypeIn = 0, int colTypeIn = 0, 
    double m0In = 0., double mWidthIn = 0., double mMinIn = 0., 
    double mMaxIn = 0., double tau0In = 0.) 
    : idSave(abs(idIn)), nameSave(nameIn), antiNameSave("void"),  
    spinTypeSave(spinTypeIn), chargeTypeSave(chargeTypeIn), 
    colTypeSave(colTypeIn), m0Save(m0In), mWidthSave (mWidthIn), 
    mMinSave(mMinIn), mMaxSave(mMaxIn), tau0Save(tau0In), 
    hasAntiSave(false), hasChangedSave(true) {setDefaults();} 
  ParticleDataEntry(int idIn, string nameIn, string antiNameIn, 
    int spinTypeIn = 0, int chargeTypeIn = 0, int colTypeIn = 0, 
    double m0In = 0., double mWidthIn = 0., double mMinIn = 0., 
    double mMaxIn = 0., double tau0In = 0.) 
    : idSave(abs(idIn)), nameSave(nameIn), antiNameSave(antiNameIn), 
    spinTypeSave(spinTypeIn), chargeTypeSave(chargeTypeIn), 
    colTypeSave(colTypeIn), m0Save(m0In), mWidthSave (mWidthIn), 
    mMinSave(mMinIn), mMaxSave(mMaxIn), tau0Save(tau0In), 
    hasAntiSave(true), hasChangedSave(true) {setDefaults();
    if (toLower(antiNameIn) == "void") hasAntiSave = false;} 

  // Initialize static data members.
  static void initStatic();

  // Initialization of some particle flags, and of constituent masses.
  void setDefaults();
  void constituentMassCalc();

  // Prepare for and pick mass according to Breit-Wigner.
  void initBWmass(); 
  double mass(); 

  // Change current values one at a time (or set if not set before).
  // (Must use set here since else name+signature clash with get methods.)
  void setName(string nameIn) {nameSave = nameIn; hasChangedSave = true;}
  void setAntiName(string antiNameIn) {antiNameSave = antiNameIn; 
    hasChangedSave = true;}
  void setSpinType(int spinTypeIn) {spinTypeSave = spinTypeIn; 
    hasChangedSave = true;}
  void setChargeType(int chargeTypeIn) {chargeTypeSave = chargeTypeIn; 
    hasChangedSave = true;}
  void setColType(int colTypeIn) {colTypeSave = colTypeIn; 
    hasChangedSave = true;}
  void setM0(double m0In) {m0Save = m0In; constituentMassCalc(); 
    hasChangedSave = true;}
  void setMWidth(double mWidthIn) {mWidthSave = mWidthIn; 
    hasChangedSave = true;}
  void setMMin(double mMinIn) {mMinSave = mMinIn; hasChangedSave = true;}
  void setMMax(double mMaxIn) {mMaxSave = mMaxIn; hasChangedSave = true;}
  void setTau0(double tau0In) {tau0Save = tau0In; hasChangedSave = true;}
  void setIsResonance(bool isResonanceIn) {isResonanceSave = isResonanceIn; 
    hasChangedSave = true;}
  void setMayDecay(bool mayDecayIn) {mayDecaySave = mayDecayIn; 
    hasChangedSave = true;}
  void setExternalDecay(bool externalDecayIn) 
    {externalDecaySave = externalDecayIn; hasChangedSave = true;}
  void setIsVisible(bool isVisibleIn) {isVisibleSave = isVisibleIn; 
    hasChangedSave = true;}

  // Change several values at the same time (or set if not set before).
  void setNames(string nameIn, string antiNameIn) {nameSave = nameIn; 
    antiNameSave = antiNameIn; hasAntiSave = true; if (toLower(antiNameIn) 
    == "void") hasAntiSave = false; hasChangedSave = true;}
  void setAll(string nameIn, string antiNameIn, int spinTypeIn = 0,
    int chargeTypeIn = 0, int colTypeIn = 0, double m0In = 0., 
    double mWidthIn = 0., double mMinIn = 0., double mMaxIn = 0., 
    double tau0In = 0.) 
    {nameSave = nameIn; antiNameSave = antiNameIn; hasAntiSave = true; 
    if (toLower(antiNameIn) == "void") hasAntiSave = false;
    spinTypeSave = spinTypeIn; chargeTypeSave = chargeTypeIn; 
    colTypeSave = colTypeIn; m0Save = m0In; mWidthSave = mWidthIn;
    mMinSave = mMinIn; mMaxSave = mMaxIn; tau0Save = tau0In; 
    setDefaults(); hasChangedSave = true;}
  void setHasChanged(bool hasChangedIn) {hasChangedSave = hasChangedIn;
    for (int i = 0; i < decay.size(); ++i) 
      decay[i].setHasChanged(hasChangedIn);}
  void rescaleBR(double newSumBR = 1.) {decay.rescaleBR(newSumBR);}

  // Give back current values. 
  int id() const { return idSave; }
  bool hasAnti() const { return hasAntiSave; } 
  string name(int idIn = 1) const { 
    return (idIn > 0) ? nameSave : antiNameSave; } 
  int spinType() const {return spinTypeSave; }
  int chargeType(int idIn = 1) const { 
    return (idIn > 0) ? chargeTypeSave : -chargeTypeSave; } 
  double charge(int idIn = 1) const { 
    return (idIn > 0) ? chargeTypeSave / 3. : -chargeTypeSave / 3.; } 
  int colType(int idIn = 1) const { 
    if (colTypeSave == 2) return colTypeSave;
    return (idIn > 0) ? colTypeSave : -colTypeSave; } 
  double m0() const { return m0Save; } 
  double constituentMass() const { return constituentMassSave; } 
  double mWidth() const { return mWidthSave; } 
  double mMin() const { return mMinSave; } 
  double m0Min() const { return (modeBWnow == 0) ? m0Save : mMinSave; } 
  double mMax() const { return mMaxSave; } 
  double m0Max() const { return (modeBWnow == 0) ? m0Save : mMaxSave; } 
  double tau0() const { return tau0Save; } 
  bool isResonance() const { return isResonanceSave; } 
  bool mayDecay() const { return mayDecaySave; } 
  bool externalDecay() const { return externalDecaySave; } 
  bool isVisible() const { return isVisibleSave; }

  // Give back other quantities.
  bool hasChanged() const { if (hasChangedSave) return true;
    for (int i = 0; i < decay.size(); ++i) 
    if (decay[i].hasChanged()) return true; return false;}
  bool useBreitWigner() const { return (modeBWnow > 0); }
  bool canDecay() const { return (decay.size() > 0);} 
  bool isLepton() const {return (idSave > 10 && idSave < 19);}
  bool isQuark() const {return (idSave != 0 && idSave < 9);}
  bool isGluon() const {return (idSave == 21);}
  bool isDiquark() const {return (idSave > 1000 && idSave < 10000 
    && (idSave/10)%10 == 0);}
  bool isHadron() const; 
  bool isMeson() const; 
  bool isBaryon() const;
  // Intermediate octet ccbar or bbar states in colour-octet model. 
  bool isOctetHadron() const {return (idSave == 9900441
    || idSave == 9900443 || idSave == 9900551 || idSave == 9900553 
    || idSave == 9910441 || idSave == 9910551); }
  int heaviestQuark(int idIn = 1) const; 
  int baryonNumberType(int idIn = 1) const;

  // The decay table.
  DecayTable decay;

private:

  // Static initialization data, normally only set once.
  static int modeBreitWigner;
  static double maxEnhanceBW;

  // Constants: could only be changed in the code itself.
  static const double MAXTAU0FORDECAY,MINMASSRESONANCE, NARROWMASS;

  // Particle data.
  int idSave;
  string nameSave, antiNameSave;
  int spinTypeSave, chargeTypeSave, colTypeSave;
  double m0Save, mWidthSave, mMinSave, mMaxSave, tau0Save, 
    constituentMassSave;
  bool hasAntiSave, isResonanceSave, mayDecaySave, externalDecaySave, 
    isVisibleSave, hasChangedSave;

  // Extra data for mass selection according to a Breit-Wigner.
  int modeBWnow;
  double atanLow, atanDif, mThr;     

  // Useful functions for string handling.
  static string toLower(const string& name);

};

//**************************************************************************

// This class holds a map of all ParticleDataEntries.

class ParticleDataTable {

public:

  // Constructor.
  ParticleDataTable() {}
 
  // Read in database from specific file.
  static bool init(string startFile = "../doc/ParticleData.xml") {
    return readXML(startFile);}

  // Overwrite existing database by reading from specific file.
  static bool reInit(string startFile, bool xmlFormat = true) {
    return (xmlFormat) ? readXML(startFile) : readFF(startFile);}

  // Initialize the handling of Breit-Wigner mass selection.
  static void initBWmass() {
    for (map<int, ParticleDataEntry>::iterator pdtEntry = pdt.begin(); 
      pdtEntry != pdt.end(); ++pdtEntry) pdtEntry->second.initBWmass(); }

  // Calculate a mass, picked according to Breit-Wigner.
  static double mass(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].mass() : 0. ; } 

  // Read or list whole (or part of) database from/to an XML file.
  static bool readXML(string inFile, bool reset = true) ; 
  static void listXML(string outFile) ; 

  // Read or list whole (or part of) database from/to a free format file.
  static bool readFF(string inFile, bool reset = true) ; 
  static void listFF(string outFile) ; 

  // Read in one update from a single line.
  static bool readString(string lineIn, bool warn = true, 
    ostream& os = cout) ; 

  // Print out table of whole database, or of only part of it.
  static void listAll(ostream& os = cout) {list(false, os);} 
  static void listChanged(ostream& os = cout) {list(true, os);} 
  static void list(bool changedOnly = false, ostream& os = cout) ; 
  static void list(int idList, ostream& os = cout) {
    vector<int> idListTemp; idListTemp.push_back(idList); 
    list( idListTemp, os);} 
  static void list(vector<int> idList, ostream& os = cout) ; 

  // Check that table makes sense, especially for decays.
  static void checkTable(int verbosity = 1, ostream& os = cout) ;
 
  // Add new entry.
  static void addParticle(int idIn, string nameIn = " ", 
    int spinTypeIn = 0, int chargeTypeIn = 0, int colTypeIn = 0, 
    double m0In = 0., double mWidthIn = 0., double mMinIn = 0., 
    double mMaxIn = 0., double tau0In = 0.) 
    { pdt[abs(idIn)] = ParticleDataEntry(idIn, nameIn, spinTypeIn,
    chargeTypeIn, colTypeIn, m0In, mWidthIn, mMinIn, mMaxIn, tau0In); }  
  static void addParticle(int idIn, string nameIn, string antiNameIn, 
    int spinTypeIn = 0, int chargeTypeIn = 0, int colTypeIn = 0, 
    double m0In = 0., double mWidthIn = 0., double mMinIn = 0., 
    double mMaxIn = 0., double tau0In = 0.) 
    { pdt[abs(idIn)] = ParticleDataEntry(idIn, nameIn, antiNameIn, 
    spinTypeIn, chargeTypeIn, colTypeIn, m0In, mWidthIn, mMinIn, 
    mMaxIn, tau0In); }  

  // Query existence of an entry.
  static bool isParticle(int idIn) {
    if (pdt.find(abs(idIn)) == pdt.end()) return false;
    if (idIn > 0 || pdt[abs(idIn)].hasAnti()) return true;
    return false; }
  
  // Return pointer to entry.
  static ParticleDataEntry* particleDataPtr(int idIn) {
    return (isParticle(idIn)) ? &pdt[abs(idIn)] : &pdt[0]; }

  // Return the id of the sequentially next particle stored in table.
  static int nextId(int idIn) ;

  // Change current values one at a time (or set if not set before).
  static void name(int idIn, string nameIn) {
    if (isParticle(idIn)) pdt[abs(idIn)].setName(nameIn); }
  static void antiName(int idIn, string antiNameIn) {
    if (isParticle(idIn)) pdt[abs(idIn)].setAntiName(antiNameIn); }
  static void spinType(int idIn, int spinTypeIn) {
    if (isParticle(idIn)) pdt[abs(idIn)].setSpinType(spinTypeIn); }
  static void chargeType(int idIn, int chargeTypeIn) {
    if (isParticle(idIn)) pdt[abs(idIn)].setChargeType(chargeTypeIn); }
  static void colType(int idIn, int colTypeIn) {
    if (isParticle(idIn)) pdt[abs(idIn)].setColType(colTypeIn); }
  static void m0(int idIn, double m0In) {
    if (isParticle(idIn)) pdt[abs(idIn)].setM0(m0In); }
  static void mWidth(int idIn, double mWidthIn) {
    if (isParticle(idIn)) pdt[abs(idIn)].setMWidth(mWidthIn); }
  static void mMin(int idIn, double mMinIn) {
    if (isParticle(idIn)) pdt[abs(idIn)].setMMin(mMinIn); }
  static void mMax(int idIn, double mMaxIn) {
    if (isParticle(idIn)) pdt[abs(idIn)].setMMax(mMaxIn); }
  static void tau0(int idIn, double tau0In) {
    if (isParticle(idIn)) pdt[abs(idIn)].setTau0(tau0In); }
  static void isResonance(int idIn, bool isResonanceIn) {
    if (isParticle(idIn)) pdt[abs(idIn)].setIsResonance(isResonanceIn); }
  static void mayDecay(int idIn, bool mayDecayIn) {
    if (isParticle(idIn)) pdt[abs(idIn)].setMayDecay(mayDecayIn); }
  static void externalDecay(int idIn, bool externalDecayIn) {
    if (isParticle(idIn)) 
    pdt[abs(idIn)].setExternalDecay(externalDecayIn); }
  static void isVisible(int idIn, bool isVisibleIn) {
    if (isParticle(idIn)) pdt[abs(idIn)].setIsVisible(isVisibleIn); }

  // Change several values at the same time (or set if not set before).
  static void names(int idIn, string nameIn, string antiNameIn) {
    if (isParticle(idIn)) pdt[abs(idIn)].setNames(nameIn, antiNameIn); }
  static void setAll(int idIn, string nameIn, string antiNameIn, 
    int spinTypeIn = 0, int chargeTypeIn = 0, int colTypeIn = 0, 
    double m0In = 0., double mWidthIn = 0., double mMinIn = 0., 
    double mMaxIn = 0.,double tau0In = 0.) 
    { if (isParticle(idIn)) pdt[abs(idIn)].setAll( nameIn, antiNameIn, 
    spinTypeIn, chargeTypeIn, colTypeIn, m0In, mWidthIn, mMinIn, mMaxIn,
    tau0In); }  
  static void hasChanged(int idIn, bool hasChangedIn) {
    if (isParticle(idIn)) pdt[abs(idIn)].setHasChanged(hasChangedIn); }
  static void rescaleBR(int idIn, double newSumBR = 1.) {
    if (isParticle(idIn)) pdt[abs(idIn)].rescaleBR(newSumBR); }
 
  // Give back current values. 
  static bool hasAnti(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].hasAnti() : false ; } 
  static string name(int idIn) {
    return (isParticle(abs(idIn))) ? pdt[abs(idIn)].name(idIn) : " "; }
  static int spinType(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].spinType() : 0 ; } 
  static int chargeType(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].chargeType(idIn) : 0 ; } 
  static double charge(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].charge(idIn) : 0 ; } 
  static int colType(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].colType(idIn) : 0 ; } 
  static double m0(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].m0() : 0. ; } 
  static double constituentMass(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].constituentMass() : 0. ; } 
  static double mWidth(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].mWidth() : 0. ; } 
  static double mMin(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].mMin() : 0. ; } 
  static double m0Min(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].m0Min() : 0. ; } 
  static double mMax(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].mMax() : 0. ; } 
  static double m0Max(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].m0Max() : 0. ; } 
  static double tau0(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].tau0() : 0. ; } 
  static bool isResonance(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].isResonance() : false ; } 
  static bool mayDecay(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].mayDecay() : false ; } 
  static bool externalDecay(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].externalDecay() : false ; }
  static bool isVisible(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].isVisible() : false ; } 
 
  // Give back other quantities.
  static bool hasChanged(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].hasChanged() : false ; } 
  static bool useBreitWigner(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].useBreitWigner() : false ; } 
  static bool canDecay(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].canDecay() : false ; }
  static bool isLepton(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].isLepton() : false ; } 
  static bool isQuark(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].isQuark() : false ; } 
  static bool isGluon(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].isGluon() : false ; } 
  static bool isDiquark(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].isDiquark() : false ; } 
  static bool isHadron(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].isHadron() : false ; } 
  static bool isMeson(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].isMeson() : false ; } 
  static bool isBaryon(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].isBaryon() : false ; } 
  static bool isOctetHadron(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].isOctetHadron() : false ; } 
  static int heaviestQuark(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].heaviestQuark(idIn) : 0 ; }  
  static int baryonNumberType(int idIn) {
    return isParticle(idIn) ? pdt[abs(idIn)].baryonNumberType(idIn) : 0 ; }  

private:

  // All particle data stored in a map.
  static map<int, ParticleDataEntry> pdt;

  // Pointer to current particle (e.g. when reading decay channels).
  static ParticleDataEntry* particlePtr;

  // Flag that initialization has been performed.
  static bool isInit;

  // Useful functions for string handling.
  static string toLower(const string& name);
  static bool boolString(string tag);
  static string attributeValue(string line, string attribute);
  static bool boolAttributeValue(string line, string attribute);
  static int intAttributeValue(string line, string attribute);
  static double doubleAttributeValue(string line, string attribute);

};
 
//**************************************************************************

} // end namespace Pythia8

#endif // Pythia8_ParticleData_H
