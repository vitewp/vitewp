class Playground {
  constructor(holder) {
    this.holder = holder;
  }
}

document
  .querySelectorAll('[data-template="demo"]')
  .forEach(holder => new Playground(holder));
