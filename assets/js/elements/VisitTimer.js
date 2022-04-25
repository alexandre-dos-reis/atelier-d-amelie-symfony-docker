import axios from "axios";
import TimeMe from "timeme.js";

export default class VisitTimer extends HTMLElement {
  constructor() {
    super();
    this.endVisit = this.endVisit.bind(this);
    this.reportTimeSpent = this.reportTimeSpent.bind(this);

    this.timeMe = TimeMe;
    this.isUserIdle = false;

    this.timeMe.initialize({
      idleTimeoutInSeconds: 30,
      currentPageName: window.location.pathname,
    });

    // Debug
    // document.addEventListener("turbo:load", () => {
    //   setInterval(() => {
    //     document.getElementById("timer").innerText =
    //       this.timeMe.getTimeOnCurrentPageInSeconds();
    //   }, 50);
    // });

    // When browser or tab are closed...
    window.addEventListener("beforeunload", () => {
      this.reportTimeSpent();
      this.endVisit();
    });
  }

  connectedCallback() {
    this.timeMe.startTimer();

    // Check every second if user is idle, if so report
    // if (this.isUserIdle === false) {
    //   setInterval(() => {
    //     if (this.timeMe.isUserCurrentlyIdle) {
    //       this.reportTimeSpent();
    //       this.endVisit();
    //       this.isUserIdle = true;
    //     }
    //   }, 1000);
    // }
  }

  // On each page change
  disconnectedCallback() {
    this.timeMe.stopTimer(); // Because this is a SPA
    this.reportTimeSpent();
    this.timeMe.resetAllRecordedPageTimes(); // Reset also in backward history
  }

  endVisit() {
    axios.get(this.dataset.endVisitUrl);
  }

  reportTimeSpent() {
    axios.post(this.dataset.reportUrl, {
      pageId: this.dataset.pageId,
      timeSpent: this.timeMe.getTimeOnCurrentPageInSeconds(),
    });
  }
}
