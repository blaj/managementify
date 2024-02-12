import './scss/main.scss';
import { startStimulusApp } from '@symfony/stimulus-bridge';
import { Application } from '@hotwired/stimulus';
import { Input, initMDB } from 'mdb-ui-kit';

initMDB({ Input });

const app: Application = startStimulusApp(
  require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.(j|t)sx?$/
  )
);

export default app;
