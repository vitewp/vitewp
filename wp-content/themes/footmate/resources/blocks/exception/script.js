class Exception {
  constructor(holder) {
    this.holder = holder;
  }
}

document
  .querySelectorAll('[data-block="exception"]')
  .forEach(holder => new Exception(holder));
