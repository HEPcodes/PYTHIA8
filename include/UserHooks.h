// UserHooks.h is a part of the PYTHIA event generator.
// Copyright (C) 2009 Torbjorn Sjostrand.
// PYTHIA is licenced under the GNU GPL version 2, see COPYING for details.
// Please respect the MCnet Guidelines, see GUIDELINES for details.

// Header file to allow user access to program at different stages.
// UserHooks: almost empty base class, with user to write the rela code. 
// MyUserHooks: derived class, only intended as an example.

#ifndef Pythia8_UserHooks_H
#define Pythia8_UserHooks_H

#include "Event.h"
#include "PartonSystems.h"
#include "PhaseSpace.h"
#include "PythiaStdlib.h"
#include "SigmaProcess.h"

namespace Pythia8 {

//**************************************************************************

// Forward reference to the PhaseSpace class.
class PhaseSpace;

//**************************************************************************

// UserHooks is base class for user access to program execution.

class UserHooks {

public:

  // Destructor.
  virtual ~UserHooks() {}

  // Initialize pointer to PartonSystems. Note: not virtual.
  void initPtr(PartonSystems* partonSystemsPtrIn) 
    {partonSystemsPtr = partonSystemsPtrIn;}

  // Possibility to modify cross section of process.
  virtual bool canModifySigma() {return false;}

  // Multiplicative factor modifying the cross section of a hard process.
  virtual double multiplySigmaBy(const SigmaProcess* sigmaProcessPtr,
    const PhaseSpace* phaseSpacePtr, bool inEvent);

  // Possibility to veto event after process-level selection.
  virtual bool canVetoProcessLevel() {return false;}

  // Decide whether to veto current process or not, based on process record.
  // Usage: doVetoProcessLevel( process).
  virtual bool doVetoProcessLevel( const Event& ) {return false;}

  // Possibility to veto MI + ISR + FSR evolution and kill event, 
  // making decision at a fixed pT scale. Useful for MLM-style matching.
  virtual bool canVetoPT() {return false;}  

  // Transverse-momentum scale for veto test. 
  virtual double scaleVetoPT() {return 0.;} 

  // Decide whether to veto current event or not, based on event record.
  // Usage: doVetoPT( iPos, event), where iPos = 0: no emissions so far;
  // iPos = 1/2/3 joint evolution, latest step was MI/ISR/FSR;
  // iPos = 4: FSR only afterwards; iPos = 5: FSR in resonance decay. 
  virtual bool doVetoPT( int , const Event& ) {return false;} 

  // Possibility to veto MI + ISR + FSR evolution and kill event, 
  // making decision after fixed number of ISR or FSR steps.
  virtual bool canVetoStep() {return false;}

  // Up to how many steps should be checked.
  virtual int numberVetoStep() {return 1;}

  // Decide whether to veto current event or not, based on event record.
  // Usage: doVetoStep( iPos, nISR, nFSR, event), where iPos as above,
  // nISR and nFSR number of emissions so far for hard interaction only.
  virtual bool doVetoStep( int , int , int , const Event& ) {return false;} 
   
  // Possibility to veto event after parton-level selection.
  virtual bool canVetoPartonLevel() {return false;}

  // Decide whether to veto current partons or not, based on event record.
  // Usage: doVetoPartonLevel( event).
  virtual bool doVetoPartonLevel( const Event& ) {return false;} 

  // Possibility to set initial scale in TimeShower for resonance decay.
  virtual bool canSetResonanceScale() {return false;}

  // Initial scale for TimeShower evolution. 
  // Usage: scaleResonance( iRes, event), where iRes is location
  // of decaying resonance in the event record. 
  virtual double scaleResonance( const int, const Event& ) {return 0.;} 

protected:

  // Constructor.
  UserHooks() {}

  // Pointer to information on subcollision parton locations.
  PartonSystems* partonSystemsPtr;

  // subEvent extracts currently resolved partons in the hard process.
  void subEvent(const Event& event, bool isHardest = true); 

  // Have one event object around as work area.
  Event workEvent;

};

//**************************************************************************

// SuppressSmallPT is a derived class for user access to program execution.
// It is a simple example, illustrating how to suppress the cross section
// of 2 -> 2 processes by a factor pT^4 / (pT0^2 + pT^2)^2, with pT0 input,
// and also modify alpha_strong scale similarly.

class SuppressSmallPT : public UserHooks {
 
public:

  // Constructor.
  SuppressSmallPT( double pT0timesMIIn = 1., int numberAlphaSIn = 0, 
    bool useSameAlphaSasMIIn = true) {isInit = false; 
    pT0timesMI = pT0timesMIIn; numberAlphaS = numberAlphaSIn; 
    useSameAlphaSasMI = useSameAlphaSasMIIn;}

  // Possibility to modify cross section of process.
  virtual bool canModifySigma() {return true;}

  // Multiplicative factor modifying the cross section of a hard process.
  // Usage: inEvent is true for event generation, false for initialization.
  virtual double multiplySigmaBy(const SigmaProcess* sigmaProcessPtr,
    const PhaseSpace* phaseSpacePtr, bool );

private:

  // Save input properties and the squared pT0 scale.
  bool   isInit, useSameAlphaSasMI;
  int    numberAlphaS;
  double pT0timesMI, pT20;

  // Alpha_strong calculation.
  AlphaStrong alphaS;

};

//**************************************************************************

} // end namespace Pythia8

#endif // Pythia8_UserHooks_H
