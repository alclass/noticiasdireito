import { CfeAppPage } from './app.po';

describe('cfe-app App', function() {
  let page: CfeAppPage;

  beforeEach(() => {
    page = new CfeAppPage();
  });

  it('should display message saying app works', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('app works!');
  });
});
