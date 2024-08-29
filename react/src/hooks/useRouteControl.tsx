// import { useEvent } from "./useEvent";
// import { useEffect } from "react";
// import { useMap } from "react-leaflet";

// import 'leaflet-routing-machine'

const UseRouteControl = () => {
  // const { mark, setDetailEvent } = useEvent();
  // const map = useMap();

  // const {srcPoint, destPoint} = mark;

  // const waypoints = [
  // srcPoint && L.latLng(srcPoint.lat, srcPoint.lng),
  //   destPoint ? L.latLng(destPoint.lat, destPoint.lng) : null,
  // ].filter((point) => point !== null);

  // const routingEventHandler = (event) => {
  //   const routes = event.routes;
  //   const data = routes[0].summary;
  //   const kmDistance = data.totalDistance / 1000;
  //   const secondTime = data.totalTime
  //   const totalTime = Math.round((secondTime % 3600) / 60);
  //   const convertionTime = totalTime > 60 ? totalTime/60 : totalTime
  //   console.log(
  //       "Total distance is " +
  //         kmDistance +
  //         " km and total time is " +
  //         secondTime +
  //         " minutes"
  //     );
  //   setDetailEvent(detailEvent => ({
  //     ...detailEvent,
  //     time: kmDistance,
  //     distance: secondTime
  //   }))
  // }

  // useEffect(() => {
  //   if (waypoints.length === 2) {
  //     const routingControl = L.Routing.control({
  //       waypoints: waypoints,
  //     });

  //     routingControl.on("routesfound", routingEventHandler);
  //     map.addControl(routingControl);

  //     return () => {
  //       // routingControl.off("routesfound", routingEventHandler)
  //       setTimeout(() => {
  //         routingControl.off("routesfound", routingEventHandler);
  //         map.removeControl(routingControl)}, 400);
  //       }
  //     }
  // }, [destPoint, map]);

  // return null;
};

export default UseRouteControl;
