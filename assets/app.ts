import './scss/main.scss';
import { startStimulusApp } from '@symfony/stimulus-bridge';
import { Application } from '@hotwired/stimulus';
import * as mdb from 'mdb-ui-kit/js/mdb.umd.min';

declare global {
  interface Window {
    mdb: any;
  }
}

window.mdb = mdb;

const app: Application = startStimulusApp(
  require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.(j|t)sx?$/
  )
);

export default app;
