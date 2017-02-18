// ProcessLevel.h is a part of the PYTHIA event generator.
// Copyright (C) 2007 Torbjorn Sjostrand.
// PYTHIA is licenced under the GNU GPL version 2, see COPYING for details.
// Please respect the MCnet Guidelines, see GUIDELINES for details.

// This file contains the main class for process-level event generation.
// ProcessLevel: administrates the selection of "hard" process.

#ifndef Pythia8_ProcessLevel_H
#define Pythia8_ProcessLevel_H

#include "Basics.h"
#include "BeamParticle.h"
#include "Event.h"
#include "Information.h"
#include "LesHouches.h"
#include "ParticleData.h"
#include "PartonDistributions.h"
#include "ProcessContainer.h"
#include "PythiaStdlib.h"
#include "ResonanceDecays.h"
#include "Settings.h"
#include "UserHooks.h"

namespace Pythia8 {
  
//**************************************************************************

// The ProcessLevel class contains the top-level routines to generate
// the characteristic "hard" process of an event.

class ProcessLevel {

public:

  // Constructor. 
  ProcessLevel() {} 

  // Destructor to delete processes in containers.
  ~ProcessLevel();
 
  // Initialization.
  bool init( Info* infoPtrIn, BeamParticle* beamAPtrIn, 
    BeamParticle* beamBPtrIn, bool doLHAin, LHAinit* lhaInitPtrIn, 
    LHAevnt* lhaEvntPtrIn, UserHooks* userHooksPtrIn, 
    vector<SigmaProcess*>& sigmaPtrs);

  // Simplified initialization: only replace LHA pointers.
  void setLHAPtrs( LHAinit* lhaInitPtrIn, LHAevnt* lhaEvntPtrIn)
    { lhaInitPtr = lhaInitPtrIn; lhaEvntPtr = lhaEvntPtrIn;}
 
  // Generate the next "hard" process.
  bool next( Event& process); 

  // Accumulate and update statistics (after possible user veto).
  void accumulate();

  // Print statistics on cross sections and number of events.
  void statistics(ostream& os = cout);

private: 

  // Generic info for process generation.
  bool   doInternal, doLHA, doSecondHard, allHardSame, noneHardSame, 
         someHardSame, doResDecays;
  int    nImpact, startColTag2;
  double sigmaND, sumImpactFac, sum2ImpactFac;

  // Vector of containers of internally-generated processes.
  vector<ProcessContainer*> containerPtrs;
  int    iContainer;
  double sigmaMaxSum;

  // Ditto for optional choice of a second hard process.
  vector<ProcessContainer*> container2Ptrs;
  int    i2Container;
  double sigma2MaxSum;

  // Pointer to various information on the generation.
  Info* infoPtr;

  // Pointers to the two incoming beams.
  BeamParticle* beamAPtr;
  BeamParticle* beamBPtr;

  // Pointers to LHAinit and LHAevnt for generating external events.
  LHAinit* lhaInitPtr;
  LHAevnt* lhaEvntPtr;

  // Pointer to userHooks object for user interaction with program.
  UserHooks* userHooksPtr;

  // SigmaTotal object needed to handle soft QCD processes.
  SigmaTotal sigmaTot;

  // ResonanceDecay object does sequential resonance decays.
  ResonanceDecays resonanceDecays;

  // Initialize the internal event generation machinery.
  bool initInternal( vector<SigmaProcess*>& sigmaPtrs, ostream& os = cout);

  // Generate the next internal event with one interaction.
  bool nextInternal( Event& process);

  // Generate the next internal event with two hard interactions.
  bool next2Internal( Event& process);

  // Append the second to the first process list.
  void combineProcessRecords( Event& process, Event& process2);

  // Read in the hard process from the Les Houches Accord.
  bool nextLHA( Event& process);

  // Add any junctions to the process event record list.
  void findJunctions( Event& process);

  // Check that colours match up.
  bool checkColours( Event& process);

  // Print statistics when two hard processes allowed.
  void statistics2(ostream& os = cout);

};

//**************************************************************************

} // end namespace Pythia8

#endif // Pythia8_ProcessLevel_H
