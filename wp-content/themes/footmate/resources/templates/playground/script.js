class Playground {
  constructor(holder) {
    this.holder = holder;
  }
}

document
  .querySelectorAll('[data-template="playground"]')
  .forEach(holder => new Playground(holder));
