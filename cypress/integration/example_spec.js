//
// **** Kitchen Sink Tests ****
//
// This app was developed to demonstrate
// how to write tests in Cypress utilizing
// all of the available commands
//
// Feel free to modify this spec in your
// own application as a jumping off point

// Please read our "Introduction to Cypress"
// https://on.cypress.io/introduction-to-cypress

describe('Grupo de tests: suite', function(){

  context('Context', function(){
    
    it('itname', function(){
      cy.visit('https://www.bluestarnutraceuticals.com/');
      cy.get('body > header > div > div.container > section > nav.main-nav.desktop-only > ul > li:nth-child(5) > a').click();
      cy.get('#login-form > div:nth-child(2) > input').type('ignacio@devguros.io');
      cy.get('#login-form > div:nth-child(3) > input').type('devgurus1');
      cy.get('#loginpop > div > div > section.aligner > div.formsubmit > a').click();
    });
  });
});
