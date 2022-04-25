import TimeMe from "timeme.js";
import axios from 'axios'

export default function manageTimer() {
  const visitTimer = document.getElementById("visit-timer");
  const endVisitUrl = visitTimer.getAttribute("end-visit-url");
  const reportUrl = visitTimer.getAttribute("report-url");
  const pageId = visitTimer.getAttribute("page-id");

  TimeMe.initialize({
    idleTimeoutInSeconds: 30,
    currentPageName: window.location.pathname,
  });

  window.addEventListener("beforeunload", () => {
    reportTimeSpent(reportUrl, pageId, TimeMe.getTimeOnCurrentPageInSeconds());
    endVisit(endVisitUrl);
  });

  document.addEventListener("turbo:before-visit", () => {
    console.log('click done !')
    TimeMe.stopTimer(); // Because this is a SPA
    reportTimeSpent(reportUrl, pageId, TimeMe.getTimeOnCurrentPageInSeconds());
  });

  //   setInterval(() => {
  //     if (TimeMe.isUserCurrentlyIdle) {
  //       reportTimeSpent();
  //       endVisit();
  //     }
  //   }, 1000);
}

const endVisit = (url) => axios.get(url);

const reportTimeSpent = (url, id, time) => {
  axios.post(url, { pageId: id, timeSpent: time });
};
